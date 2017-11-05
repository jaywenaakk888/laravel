<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;
class UploadController extends Controller
{
    /**
     * 处理图片上传
     */
    public function picture(Request $request){
        if ($request->isMethod('post')) {
			
			$file = $request->file('picture');

			// 文件是否上传成功
			if ($file->isValid()) {

				// 获取文件相关信息
				$originalName = $file->getClientOriginalName(); // 文件原名
				$ext = $file->getClientOriginalExtension();     // 扩展名
				$realPath = $file->getRealPath();   //临时文件的绝对路径
				$type = $file->getClientMimeType();     // image/jpeg
				if($ext=='jpg'||'jpeg'||'png'||'gif'||'bmp'){ //
					// 上传文件
					$filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;

					// 使用我们新建的uploads本地存储空间（目录）
					$bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));

					return response()->json([
						'filename' => $filename,
					]);
				}else{
					return response()->json([
						'error' => '只允许上传图片！',
					]);
				}
				
			}else{
				return response()->json([
					'error' => '上传失败！',
				]);
			}

		}
    }
}
