<?php

namespace Modules\Api\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class IDModel extends Model
{
    public static function insert()
    {
        $data = json_decode($_POST['dataInsert']);

        foreach ($data as $k => $v) { 
            $check_isset = DB::table('id_data_tiki')->where('id_tiki', $v->id)->count();

            if ($check_isset == 0 ) {
                $id = DB::table('id_data_tiki')->insertGetId(['id_tiki' => $v->id]);
                echo '   || ID save -->  '.$id. '  -->  '.$v->id;
            } else echo '  exit data. *** '.$v->id;
        }
    }

    public static function get_menu()
    {
        $data = DB::table('menu')->where('level', '=', 'end')->paginate(15);
        return response()->json($data, 200);
    }


    protected $table    = 'id_data_tiki';
    protected $fillable = [
        'id',
        'id_tiki',
        'created_at'
    ];
}
