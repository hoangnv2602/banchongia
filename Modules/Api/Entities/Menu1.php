<?php

namespace Modules\Api\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;


class Menu1 extends Model
{

    public static function create_menu()
    {
        $data = json_decode($_POST['dataInsert']);
        
        foreach ($data as $k => $v) {
            if (isset($v->title)) {
                $v->title = str_replace("&amp;", "&", $v->title);
                $v->title = str_replace("&nbsp;", "", $v->title);

                $para_chec = array(
                    'title' => trim($v->title)
                );
                if ($v->parent !== 0 ) {
                    $v->parent = str_replace("&amp;", "&", $v->parent);
                    $para_chec['parent_check'] = 'aaaaaaaaaaaaa';
                }

                $check = DB::table('menu')->where($para_chec)->count();

                if ($check == 0) {

                    $array_insert = array( 
                        'title' => trim($v->title),
                        'slug' => Menu1::convert_vi_to_en(trim($v->title)),
                        'source' => $v->link,
                        'total' => $v->total,
                        'parent_check' => ($v->parent !== 0 ) ? $v->parent : null,
                        'parent_total' => 0
                    );

                    $check_slug = DB::table('menu')->where('slug', 'like', '%'.Menu1::convert_vi_to_en(trim($v->title)).'%')->count();

                    if ($check_slug > 0 ) $array_insert['slug'] = $array_insert['slug'].'-'.($check_slug+1);

                    if ($v->type == 'main') $array_insert['total'] = 0;

                    $insert_id = DB::table('menu')->insertGetId($array_insert);
                    
                    $parent_id = 0;

                    if ($v->parent !== 0 ) {
                        $v->parent = str_replace("&amp;", "&", $v->parent);
                        $parent_id = DB::table('menu')
                                        ->join('menu_relationship', 'menu.id', '=', 'menu_relationship.id_menu')
                                        ->where('menu.title', trim($v->parent))
                                        ->orderBy('menu.id', 'DESC')
                                        ->select('menu_relationship.id_menu')
                                        ->get();
                                        
                        $parent_id =   ( $parent_id->count() > 0) ? $parent_id[0]->id_menu  : 0;
                    }

                    $array_insert_r = array(
                        'id_menu' => $insert_id,
                        'parent' => $parent_id
                    );

                    if ($v->type == 'main') $array_insert_r['type'] = 'main';

                    if ($v->type == 'category') $array_insert_r['type'] = 'category';

                    if ($v->type == 'main_1') $array_insert_r['type'] = 'main';

                    $insert_id = DB::table('menu_relationship')->insertGetId($array_insert_r);
                    if (isset($v->parent_update)) {
                        DB::table('menu')
                                ->where('id', $v->parent_update)
                                ->update(['parent_total' => 1]);
                    }

                    echo "---------> ".$insert_id. '     ';
                } else { 
                    echo 'exit data.. \n';
                }
            } else {
                if (isset($v->parent_update)) {
                    DB::table('menu')
                            ->where('id', $v->parent_update)
                            ->update(['level' => 'end', 'parent_total' => 1]);
                }
                echo 'update to : '. $v->parent;
            }
        }
    }

    

    public static function insert()
    {
        $data = json_decode($_POST['dataInsert']);
        
        foreach ($data as $k => $v) {
            
            $check_brand = DB::table('brand')->where('brand', trim($v->brand_id))->get('id');
            if (count($check_brand) == 0) $v->brand_id = DB::table('brand')->insertGetId( array( 'brand' => trim( $v->brand_id ) ) );
            else $v->brand_id = $check_brand[0]->id;

            $v->thumnail = base64_encode( file_get_contents($v->thumnail) );

            $check_product = DB::table('product')->where(['title' => trim($v->title), 'category_id' => $v->category_id])->count();
            
            $id = 0;


            // print_r($v);
            // die;
            if ( $check_product == 0 ) $id = DB::table('product')->insertGetId((array)$v);
            
            echo  $id;
        }

    }

    public static function check()
    {
        
        $data = DB::table('menu')
                ->join('menu_relationship', 'menu.id', '=', 'menu_relationship.id_menu')
                ->where('menu.parent_total', 0)
                ->select('menu.*')
                ->get();
        return response()->json($data, 200);
    }

    public static function get_menu()
    {
        $data = DB::table('menu')->where('level', 'end')->paginate(15);
        return response()->json($data, 200);
    }

    public static function convert_vi_to_en($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        $str = str_replace(",", "", $str);
        $str = str_replace(" &", "", $str);
        $str = str_replace(" -", "", $str);
        $str = str_replace("(", "", $str);
        $str = str_replace(")", "", $str);
        $str = str_replace("/", " ", $str);
        $str = str_replace(" / ", " ", $str);
        $str = str_replace(" ", "-", $str);
        return strtolower($str);
    }

    protected $table    = 'menu';
    protected $fillable = [
        'id',
        'title',
        'slug',
        'source',
        'created_at',
        'updated_at'
    ];
}
