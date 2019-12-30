<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Company;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Response;
use App\Models\User;

use Spatie\Permission\Models\Role;


class UserController extends AppBaseController
{
  /** @var  UserRepository */
  private $userRepository;

  public function __construct(UserRepository $userRepo)
  {
    $this->userRepository = $userRepo;
  }

  /**
   * Display a listing of the User.
   *
   * @param Request $request
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $users = $this->userRepository->all();
    $companies = Company::all();
    return view('users.index')
      ->with('users', $users)->with('companies', $companies);
  }

  /**
   * Show the form for creating a new User.
   *
   * @return Response
   */
  public function create()
  {
    return view('users.create');
  }

  /**
   * Store a newly created User in storage.
   *
   * @param CreateUserRequest $request
   *
   * @return Response
   */
  public function store(CreateUserRequest $request)
  {
    $request->merge(['password' => Hash::make($request['password'])]);
    $input = $request->all();

    $user = $this->userRepository->create($input);

    Flash::success('Usuario guardado correctamente.');

    return redirect(route('users.index'));
  }

  /**
   * Display the specified User.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    $user = $this->userRepository->find($id);

    if (empty($user)) {
      Flash::error('User not found');
      return redirect(route('users.index'));
    }

    return view('users.show')->with('user', $user);
  }

  /**
   * Show the form for editing the specified User.
   *
   * @param int $id
   *
   * @return Response
   */
  public function edit($id)
  {
    $user = $this->userRepository->find($id);

    if (empty($user)) {
      Flash::error('User not found');

      return redirect(route('users.index'));
    }

    return view('users.edit')->with('user', $user);
  }

  /**
   * Update the specified User in storage.
   *
   * @param int $id
   * @param UpdateUserRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateUserRequest $request)
  {
    $user = $this->userRepository->find($id);

    if (empty($user)) {
      Flash::error('User not found');
      return redirect(route('users.index'));
    }

    $user = $this->userRepository->update($request->all(), $id);

    Flash::success('User updated successfully.');

    return redirect(route('users.index'));
  }

  /**
   * Remove the specified User from storage.
   *
   * @param int $id
   *
   * @throws \Exception
   *
   * @return Response
   */
  public function destroy($id)
  {
    $user = $this->userRepository->find($id);

    if (empty($user)) {
      Flash::error('User not found');

      return redirect(route('users.index'));
    }

    $this->userRepository->delete($id);

    Flash::success('User deleted successfully.');

    return redirect(route('users.index'));
  }

	public function newRole($id)
	{
		$user = $this->userRepository->find($id);
		$roles = Role::all();

		if (empty($user)) {
			Flash::error('Usuario no encontrado');
			return redirect(route('users.index'));
		}

		return view('users.roles.assign',compact('user', 'roles'));
	}

	public function assignRole($id, Request $request)
	{
		$user = $this->userRepository->find($id);
		$role = Role::findById($request['role_id']);
		$user->assignRole($role);

		if (empty($request)) {
			Flash::error('No hay solicitud');
			return redirect(route('users.index'));
		}

		return redirect(route('users.index'));
	}

	public function unassignRole($id, Request $request)
	{
		$user = $this->userRepository->find($id);
		$role = Role::findByName($request['role_id']);
		$user->removeRole($role);

		if (empty($request)) {
			Flash::error('No hay solicitud');
			return redirect(route('users.index'));
		}
		return redirect(route('users.index'));
	}

	public function newCompany(User $user)
	{
		// $user = $this->userRepository->find($id);
		$companies = Company::all();

		if (empty($user)) {
			Flash::error('Usuario no encontrado');
			return redirect(route('users.index'));
		}

		return view('users.companies.assign',compact('user', 'companies'));
	}

	public function assignCompany(User $user, Request $request)
	{
		// $user = $this->userRepository->find($id);
		// $company = Company::find($request['company_id']);
		$user->company_id = $request['company_id'];

		if (empty($request)) {
			Flash::error('No hay solicitud');
			return redirect(route('users.index'));
		}

		$user->save();

		return redirect(route('users.show', $user));
	}

	public function unassignCompany(User $user, Request $request)
	{
		$user->company_id =NULL;

		if (empty($request)) {
			Flash::error('No hay solicitud');
			return redirect(route('users.index'));
		}

		$user->save();

		return redirect(route('users.show', $user));
	}
}
