<?php

namespace Modules\Api\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{

    public static function create_menu()
    {
        $data = json_decode($_POST['dataInsert']);
        foreach ($data as $k => $v) {
            $check = DB::table('menu')->where('title', trim($v->title))->count();
            
            if ($check == 0) {
                $array_insert = array( 
                    'title' => trim($v->title),
                    'slug' => Menu::convert_vi_to_en(trim($v->title)),
                    'source' => $v->link
                );

                $insert_id = DB::table('menu')->insertGetId($array_insert);
                return $insert_id;
                $parent_id = 0;

                if ($v->parent !== 0 ) {
                    $parent_id = DB::table('menu')
                                    ->join('menu_relationship', 'menu.id', '=', 'menu_relationship.id_menu')
                                    ->where('menu.title', trim($v->parent))
                                    ->select('menu_relationship.id_menu')
                                    ->get();
                                    
                    $parent_id =  $parent_id[0]->id_menu;
                }

                $array_insert_r = array(
                    'id' => $id,
                    'id_menu' => $id,
                    'parent' => $parent_id
                );

                if ($v->type == 'main') $array_insert_r['type'] = 'main';

                if ($v->type == 'category') $array_insert_r['type'] = 'category';

                if ($v->type == 'category_child') {
                    
                    $array_insert_r['type'] = 'category';
                
                }

                if ($v->type == 'main_1') $array_insert_r['type'] = 'main';

                $insert_id = DB::table('menu_relationship')->insert($array_insert_r);
            }
        }
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
