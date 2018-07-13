<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Table;
use App\Role;
use App\User;
use Storage;
use Response;
use Carbon\Carbon;
use ZipArchive;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;

class AdminController extends Controller
{
    /**
    *
    *   Changes for admin
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function allmenu (){
        $menus = Menu::all();

        foreach ($menus as $key => $value) {
            $dir    = public_path() . '/cim/'.$value->id;
            if (is_dir($dir)) {
                $value->img = '/cim/'.$value->id.'/'.scandir($dir)[2];
            }else{
                $value->img = false;   
            }
        }
        // dd($menus);
        return view('menu_admin',compact('menus'));
    }

    /**
    *
    *   Changes for admin
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function alltable (){
        $tables = Table::all();
        return view('table_admin',compact('tables'));
    }

    /**
    *
    *   Changes for admin
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function rolesetting (){
     	$roles = Role::all();
     	$users = User::all();
     	return view('role_admin',compact('roles','users'));	 
    }
    	
    /**
    *
    *   Changes for Index
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function generateqr (Request $request,$table){
        $parameters = $request->all();
        if ($table == 'all') {
            $tables = Table::all();
            $data = [];
            foreach ($tables as $key => $table) {
                $data[$key] = file_get_contents("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$_SERVER['SERVER_NAME'].'/customer/option?tid='.$table->label."&choe=UTF-8");
                Storage::put($tables->first()->id.'/'.$table->label.'.jpg', $data);
            }
            $zipFileName = 'all qr ('.Carbon::now()->timestamp.').zip';
            $zip = new ZipArchive();
            $zip->open(storage_path($zipFileName), ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $rootPath = storage_path('app/'.$tables->first()->id.'/');
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file)
            {

                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // dd($file->getFilename(),$name);
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $file->getFilename());
                    // $zip->addFile($filePath, $file->name);
                }
            }

            // Zip archive will be created only after closing object
            $oldmask = umask(0);
            $zip->close();
            umask($oldmask);
            # code...
            // Set Header
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
            $filetopath=storage_path($zipFileName);
            // Create Download Response
            if(file_exists($filetopath)){
                return response()->download($filetopath,$zipFileName,$headers)->deleteFileAfterSend(true);
            }
        }else{
            $table = Table::find($table);
            $data = file_get_contents("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$_SERVER['SERVER_NAME'].'/customer/option?tid='.$table->label."&choe=UTF-8");
            Storage::put($table->label.'.jpg', $data);
            return Response::download(storage_path('app/'.$table->label.'.jpg'))->deleteFileAfterSend(true);
        }

    }
        
}
