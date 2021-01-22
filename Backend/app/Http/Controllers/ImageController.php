<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Filters files or folders
     * 
     * @param  string   $mode
     * @param  array    $inArray
     * @return array
     */
    private function filter(string $mode, array $inArray)
    {
        switch ($mode) {
            case 'files':
                $filterFunc = function($in) { 
                    return is_dir("images/$in"); 
                };
                break;
            case 'folders':
                $filterFunc = function($in) { 
                    return !is_dir("images/$in"); 
                };
                break;
            default:
                return $inArray;
        }
        
        return array_values(array_filter($inArray, $filterFunc));
    }

    /**
     * Creates array of imagepaths
     * 
     * @param   string  $folder
     * @param   array   $inArray
     * @return  array
     */
    private function createFileArray(string $folder, array $inArray)
    {
        return array_map(function($in) use ($folder) {
            return "/images/$folder$in";
        }, $inArray);
    }
    

    /**
     * Get folders
     * 
     * @return Response
     */
    public function getFolders()
    {
        $folders = $this->filter('files', array_diff(scandir('images'), ['.', '..']));
        return response()->json([ 'data' => $folders ], 200);
    }

    /**
     * Get files inside a folder
     * 
     * @param  string   $folder
     * @return Response
     */
    public function getFiles(string $folder)
    {
        if ($folder === 'root') $folder = '';
        else $folder = "$folder/";
        $files = $this->filter('folders', array_values(array_diff(scandir("images/$folder"), ['.', '..'])));
        $files = $this->createFileArray($folder, $files);

        return response()->json(['data' => $files], 200);
    }

    /**
     * Handels fileupload and returns files to user
     * 
     * @param  Request  $request
     * @param  string   $folder
     * @return Response
     */
    public function handleFileUpload(Request $request, $folder)
    {
        $this->validate($request, [
            'files' => 'required',
            'files.*' => 'image'
        ]);

        if ($folder === 'root') $folder = '';
        else $folder = "$folder/";

        foreach ($request->file('files') as $file) {
            
            $file->move("images/$folder", uniqid().'.'.$file->getClientOriginalExtension());
        }
        
        $files = $this->filter('folders', array_values(array_diff(scandir("images/$folder"), ['.', '..'])));
        $files = $this->createFileArray($folder, $files);

        return response()->json(['message' => 'Kuva(t) lisätty', 'data' => $files], 200);
    }

    /**
     * Get files inside a folder
     * 
     * @param  Request  $request
     * @return Response
     */
    public function handleFileDelete(Request $request)
    {
        $this->validate($request, ['path' => 'string']);

        if (!unlink(substr($request->input('path'), 1)))
            return response()->json(['message' => 'Jotain meni vikaan kuvaa poistaessa'], 500);

        return response()->json(['message' => 'Kuva poistettu'], 200);
    }
}