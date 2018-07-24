<?php

namespace App\Http\Controllers\FD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Google\Cloud\Core\ServiceBuilder;

class FDController extends Controller
{
    public function detectFaces()
    {
        $cloud = new ServiceBuilder([
            'keyFilePath' => base_path('FD.json'),
            'projectId' => 'facial-recognition-laravel-app '
        ]);

        $vision = $cloud->vision();

        $output = imagecreatefromjpeg(public_path('images/friends.jpg'));

        $image = $vision->image(file_get_contents(public_path('images/friends.jpg')), ['FACE_DETECTION']);
        $results = $vision->annotate($image);

        foreach ($results->faces() as $face) {
            $vertices = $face->boundingPoly()['vertices'];

            $x1 = $vertices[0]['x'];
            $y1 = $vertices[0]['y'];
            $x2 = $vertices[2]['x'];
            $y2 = $vertices[2]['y'];

            imagerectangle($output, $x1, $y1, $x2, $y2, 0x00ff00);
        }

        header('Content-Type: image/jpeg');

        imagejpeg($output);
        imagedestroy($output);
    }
}