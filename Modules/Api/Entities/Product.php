<?php

namespace Modules\Api\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{

    public static function getlist()
    {
        $data = DB::table('id_data_tiki')->where('get_', 0)->paginate(200);
        return response()->json($data, 200);
    }

    public static function insert()
    {
        $data = json_decode($_POST['dataInsert'])[0];

        $array_insert = array(
            'name' => $data->name,
            'thumnail' => file_get_contents($data->thumbnail_url),
            'description' => $data->description
        );

        // preg_match_all('/<img(.*?)src="(.*?)"/',$data->description, $result);

        // if (count($result[2]) > 0 ) {
        //     foreach ($result[2] as $v) {
        //         $data = base64_encode(file_get_contents($v));
        //         $array_insert['description'] = str_replace($v, 'data:image/png;base64, '. $data, $array_insert['description']);
        //     }
            
        // }

        $productset_group_name = $data->productset_group_name;

        $productset_group_name = str_replace("&amp;", "&", $productset_group_name);
        $productset_group_name = str_replace("&nbsp;", "", $productset_group_name);

        if (strpos($productset_group_name, 'I/O Port Cards') !== false) $str = Product::make_cate_gory($productset_group_name, 'I/O Port Cards');
        elseif (strpos($productset_group_name, 'Bộ Phát Wifi Di Động 3G/4G - Mifi') !== false) $str = Product::make_cate_gory($productset_group_name, 'Bộ Phát Wifi Di Động 3G/4G - Mifi');
        elseif (strpos($productset_group_name, 'Gậy tự sướng/ Selfie') !== false) $str = Product::make_cate_gory($productset_group_name, 'Gậy tự sướng/ Selfie');
        elseif (strpos($productset_group_name, 'SIM Data - SIM 3G / 4G') !== false) $str = Product::make_cate_gory($productset_group_name, 'SIM Data - SIM 3G / 4G');
        elseif (strpos($productset_group_name, 'Bộ Chuyển Đổi Tín Hiệu HDMI/VGA/DVI/DP') !== false) $str = Product::make_cate_gory($productset_group_name, 'Bộ Chuyển Đổi Tín Hiệu HDMI/VGA/DVI/DP');
        elseif (strpos($productset_group_name, 'Bàn sofa/salon') !== false) $str = Product::make_cate_gory($productset_group_name, 'Bàn sofa/salon');
        elseif (strpos($productset_group_name, 'Bộ sofa/salon') !== false) $str = Product::make_cate_gory($productset_group_name, 'Bộ sofa/salon');
        elseif (strpos($productset_group_name, 'Ghế sofa/salon') !== false) $str = Product::make_cate_gory($productset_group_name, 'Ghế sofa/salon');
        elseif (strpos($productset_group_name, 'Thanh chắn cầu thang/cửa') !== false) $str = Product::make_cate_gory($productset_group_name, 'Thanh chắn cầu thang/cửa');
        elseif (strpos($productset_group_name, 'Chăn/mền') !== false) $str = Product::make_cate_gory($productset_group_name, 'Chăn/mền');
        elseif (strpos($productset_group_name, 'Drap/Ga/Bao gối') !== false) $str = Product::make_cate_gory($productset_group_name, 'Drap/Ga/Bao gối');
        elseif (strpos($productset_group_name, 'Máy đưa nôi/võng') !== false) $str = Product::make_cate_gory($productset_group_name, 'Máy đưa nôi/võng');
        elseif (strpos($productset_group_name, 'Nôi xách tay/Nôi xe hơi') !== false) $str = Product::make_cate_gory($productset_group_name, 'Nôi xách tay/Nôi xe hơi');
        elseif (strpos($productset_group_name, 'Giá úp bình sữa/Khay') !== false) $str = Product::make_cate_gory($productset_group_name, 'Giá úp bình sữa/Khay');
        elseif (strpos($productset_group_name, 'Túi giữ nhiệt (bình sữa/bình nước)') !== false) $str = Product::make_cate_gory($productset_group_name, 'Túi giữ nhiệt (bình sữa/bình nước)');
        else $str = Product::make_cate_gory($productset_group_name, '');

        echo $str;


        // echo $data->description;
    }

    public static function make_cate_gory($productset_group_name, $replace)
    {
        if ($replace != ''){
            $arr = str_replace('/'.$replace, '', $productset_group_name );
        } else { $arr = $productset_group_name; }

        $arr = explode('/', $arr);
        if ($replace != '') $str = "('".implode("', '",$arr)."', '".$end."')";
        else $str = "('".implode("', '",$arr)."')";

        return $str;
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


    protected $fillable = [];
}
