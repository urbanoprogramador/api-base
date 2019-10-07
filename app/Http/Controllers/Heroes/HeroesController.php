<?php

namespace App\Http\Controllers\Heroes;

use App\Http\Controllers\Controller;
use App\Model\Heroe;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class HeroesController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->showArray(Heroe::all()->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name'=>'required|string'
        ]);
        $heroe=new Heroe;
        $input=$request->only('name');
        $heroe->fill($input);
        $heroe->save();
        return $this->showOne($heroe);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Heroe  $heroe
     * @return \Illuminate\Http\Response
     */
    public function show(Heroe $hero)
    {
        //
        return $this->showOne($hero);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Heroe  $heroe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Heroe $hero)
    {
        //
        $request->validate([
            'name'=>'required|string'
        ]);
        $hero->name=$request->name;
        $hero->save();

        return $this->showOne($hero);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Heroe  $heroe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Heroe $hero)
    {
        //
        $hero->delete();
        return $this->showOne($hero);
    }
}
