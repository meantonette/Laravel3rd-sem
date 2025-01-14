<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer; //tinawag via model
use Illuminate\Support\Facades\View; //ginagamit ito para tawagin extension na ito
use Illuminate\Support\Facades\Validator;// same dito
use Illuminate\Support\Facades\Redirect;// and dito
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $customers = Customer::orderBy('id','ASC')->get();
        $customers = Customer::withTrashed()->orderBy('id','DESC')->paginate(10);
        // dd($customers);
        return View::make('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('customer.create'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [  'title' =>'required|min:2',
        'lname'=>'required|alpha',
        'fname'=>'required',
        'addressline'=>'required',
        'phone'=>'numeric',
        'town'=>'required',
        'zipcode'=>'required'];

        $messages = [
            'required' => 'Ang :attribute field na ito ay kailangan',
            'min' => 'masyadong maigsi ang :attribute',
            'alpha' => "pawang letra lamang",
            'fname.required' => 'ilagay ang pangalan'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        // dd($validator);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);  
        }

        $path = Storage::putFileAs('images/customer', $request->file('image'),
        $request->file('image')->getClientOriginalName());

        $request->merge(["img_path"=>$request->file('image')->getClientOriginalName()]);

        // return redirect()->back()->withInput()->withErrors($validator);
        Customer::create($request->all());
        return Redirect::to('customer')->with('success','New Customer added!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
         return view('customer.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $validator = Validator::make($request->all(), Customer::$rules, Customer::$messages);
     
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $customer->update($request->all());
        return Redirect::to('customer')->with('success','Customer Updated!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return Redirect::to('/customer')->with('success', 'Customer Deleted!');
    }

    public function restore($id)
    {
        
        Customer::withTrashed()->where('id',$id)->restore();
        return  Redirect::route('customer.index')->with('success',
        'customer restored successfully!');

    }
}
