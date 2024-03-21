<?php

namespace App\Http\Controllers;

use App\Models\MateriasDocente;
use Illuminate\Http\Request;

class TeacherSubject extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(session()->has('administrador')){
        $request->validate([
            'teacherId' => 'required',
        ]);

        $subject = new MateriasDocente();
        
        $subject->idDocente = $request->input('teacherId');
        $subject->idMateria = $request->input('newSubject');

        if($subject->save())
        {
            return to_route('teachers.edit',$request->input('teacherId'))->with('materiaAgregada','La materia fue registrada correctamente');
        }}else{
            return view('layout.403');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
