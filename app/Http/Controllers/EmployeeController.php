<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('index');
    }


    //handle insert employee ajax request
    public function store(Request $request)
    {
       
       $empData = [
           'name' => $request->name,
           'email' => $request->email
           
       ];
       Employee::create($empData);
       return response()->json([
           'status' => 200
       ]);
    }
    
    //handle fetch all employees ajax request
    public function fetchAll()
    {
        $emps = Employee::all();
        $output = '';
        if ($emps->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>

                </tr>
            </thead>
                <tbody>';

            foreach ($emps as $key => $emp) {
                $output .= '<tr>
                    <td>'.$emp->id.'</td>
                    <td>'.$emp->name.'</td>
                    <td>'.$emp->email.'</td>
                    <td>
                        <a href="#" id="'.$emp->id.'" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                            <i class="bi-pencil-square h4"></i>
                        </a>

                        <a href="#" id="'.$emp->id.'" class="text-danger mx-1 deleteIcon">
                            <i class="bi-trash h4"></i>
                        </a>
                    </td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        }else{
            echo '<h1 class="text-center text-secondary mt-5">No record present in the database!</h1>';
        }
    }
    //handle edit employee ajax request

    public function edit(Request $request)
    {
        $id = $request->id;
        $emp = Employee::find($id);
        return response()->json($emp);
    }

    public function update(Request $request)
    {
        $fileName = '';
        $emp = Employee::find($request->emp_id);
        $empData = [
            'name' => $request->name,
            'email' => $request->email
        ];
        $emp->update($empData);
        return response()->json([
            'status' => 200
        ]);
    }

    //handle delete employee ajax request
    public function delete(Request $request){
        $id = $request->id;
        $emp = Employee::find($id);
        Employee::destroy($id);
    }
}
