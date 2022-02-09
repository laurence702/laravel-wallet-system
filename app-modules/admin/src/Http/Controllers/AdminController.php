<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\User\Models\User;
use Carbon\Exceptions\Exception;
use App\Http\Controllers\Controller;
use Modules\Admin\Services\AdminServices;

class AdminController extends Controller
{
    public function __construct(AdminServices $AdminServices){
        $this->AdminServices = $AdminServices;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->AdminServices->showAll();
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function banUser($user_id){ //ban user feature
        return $this->AdminServices->disableUser($user_id);
    }


    public function fundUserWallet(Request $request)
    {
      return $this->AdminServices->AdminCreditUserWallet($request);
    }

    public function verifyUser(Request $request)
    {
       return $this->AdminServices->AdminVerifyUser($request);
    }

    public function liftBan($user_id)
    {
        return $this->AdminServices->liftUserBan($user_id);
    }
}
