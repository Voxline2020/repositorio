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
use Mail;

use Spatie\Permission\Models\Role;


class UserController extends AppBaseController
{
  /** @var  UserRepository */
  private $userRepository;

  public function __construct(UserRepository $userRepo)
  {
		$this->middleware('admin');
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
    $users = User::paginate();
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
  public function store(Request $request)
  {
		//validacion de campos
		if($request->email==null){
			Flash::error('El campo "Email" es requerido.');
		}
		if($request->password==null){
			Flash::error('El campo "Contraseña" es requerido.');
		}
		if($request->rut==null){
			Flash::error('El campo "Rut" es requerido.');
		}
		if($request->name==null){
			Flash::error('El campo "Nombre" es requerido.');
		}
		if($request->lastname==null){
			Flash::error('El campo "Apellido" es requerido.');
		}
		if($request->email!=null&&$request->pasword!=null&&$request->rut!=null&&$request->name!=null&&$request->lastname!=null){
			//envio de notificacion email
			$from = 'voxline.notification@gmail.com';
			$fromName = 'Notificaciones VxCMS';
			$subject = 'Creación de usuario exitoso.';
			$for = $request->email;
			$forName = ''.$request->name.' '.$request->lastname.'';
			$data = $request->all();
			Mail::send('layouts.notifycreateuser',$data,
			function($message)
			use($subject,$for,$forName,$from,$fromName)
			{
				$message->from($from,$fromName );
				$message->to($for, $forName);
				$message->subject($subject);
				$message->priority(3);
			});
			//creacion de usuario a db
			$request->merge(['password' => Hash::make($request['password'])]);
			$input = $request->all();
			$user = $this->userRepository->create($input);
			//envio de notificacion a la vista
			Flash::success('Usuario guardado correctamente.');
			return redirect(route('users.index'));
		}
		return redirect(url()->previous());
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
	public function filter_by(Request $request)
	{
		$companies = Company::all();
		if($request->nameFilter==null&&$request->emailFilter==null){
			Flash::error('Debes ingresar almenos un filtro para la busqueda.');
			return redirect(route('users.index'));
		}
		if($request->nameFilter!=null){
			$users = User::where('name','like',"%$request->nameFilter%")->paginate();
		}
		if($request->emailFilter!=null){
			$users = User::where('email','like',"%$request->emailFilter%")->paginate();
		}
		if($users->count()==0){
			Flash::info('No se encontro resultados.');
			return redirect(route('users.index'));
		}
		return view('users.index')->with('users', $users)->with('companies', $companies);
	}
}
