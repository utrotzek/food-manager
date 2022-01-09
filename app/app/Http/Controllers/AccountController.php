<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\CalendarStoreRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\AccountResourceCollection;
use App\Http\Resources\CalendarResource;
use App\Http\Resources\CalendarResourceCollection;
use App\Interfaces\RepositoryInterfaces\AccountRepositoryInterface;
use App\Models\Account;
use App\Models\Calendar;
use App\Repositories\AccountRepository;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    protected AccountRepositoryInterface $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return new Response(new AccountResourceCollection($this->accountRepository->all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountStoreRequest $request)
    {
        $response = [];
        $newItem = $this->accountRepository->create($request->input());

        $response['item'] = new AccountResource($newItem);
        $response['message'] = sprintf('Account %1$s successfully created', $newItem['title']);
        return new Response($response);
    }

    /**
     * Return a certain good
     */
    public function show(Account $account): Response
    {
        return new Response(new AccountResource($account));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountStoreRequest $request, Account $account)
    {
        $response = [];
        $newItem = $this->accountRepository->update($request->input(), $account);
        $response['item'] = new AccountResource($newItem);
        $response['message'] = sprintf('Account %1$s successfully updated', $newItem['title']);
        return new Response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return new Response([
            'message' => sprintf('Account %1$s successfully deleted', $account['title'])
        ]);
    }
}
