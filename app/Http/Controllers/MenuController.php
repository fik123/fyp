<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parameter = $request->all();
        
        $fields = [
                'name' => $parameter['name'],
                'price' => $parameter['price'],
                'description' => $parameter['description'],
                'time_taken' => $parameter['time_taken']*1000,
            ];
        $menubaru = new Menu;
        $menubaru = $menubaru->create($fields);
        // $menubaru->name = $parameter['name'];
        // $menubaru->price = $parameter['price'];
        // $menubaru->description = $parameter['description'];
        // $menubaru->time_taken = $parameter['time_taken']*1000;
        $photo = $parameter['menu_img'];
        // $attachment = $request->file('attachment');

        // // photo
        if (!is_null($photo)) {
        //     $imageName = Null;
        // }else{
            $imgextension = $photo->getClientOriginalExtension();
            
            $oldmask = umask(0);
            mkdir(public_path('/cim/'.$menubaru->id), 0777, true);
            umask($oldmask);
            // $imageName = $newpassenger->id . '_' . rand(11111,99999) . '.' . $extension;
            $imageName = \Carbon\Carbon::now()->timestamp . '.' . $imgextension;
            $photo->move(
                base_path() . '/public/cim/'.$menubaru->id, $imageName
            );
        }
        $menubaru->save();
        return redirect()->back()->withSuccess('New Menu has been Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
