<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\TypeOfDocuments;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminEmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verificar permisos
        Gate::authorize('manage-users');

        $query = User::with('roles');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('manage-users');

        $roles = UserRole::toArray();
        $documentTypes = TypeOfDocuments::toArray();

        return view('admin.users.create', compact('roles', 'documentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'document_type' => ['required', 'integer', new Enum(TypeOfDocuments::class)],
            'document_number' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:10', 'unique:users'],
            'password' => ['required', Password::default(), 'confirmed'],
            'role' => ['required', 'string', new Enum(UserRole::class)],
        ], [
            'email.unique' => 'Ya existe un usuario con este correo electrónico.',
            'document_number.unique' => 'Ya existe un usuario con este número de documento.',
            'phone.unique' => 'Ya existe un usuario con este número de teléfono.',
        ]);

        // Validación de seguridad: No permitir crear usuarios con el mismo email que clientes
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return back()->withErrors([
                'email' => 'Ya existe una cuenta con este correo electrónico. Por seguridad, no se pueden duplicar emails entre usuarios administrativos y clientes.'
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            // En producción, NO verificar automáticamente
            'email_verified_at' => app()->environment('production') ? null : now(),
        ]);

        // Asignar rol
        $user->assignRole($request->role);

        // Enviar notificación de verificación de email si estamos en producción
        if (app()->environment('production')) {
            try {
                $user->notify(new AdminEmailVerificationNotification());

                Log::info('Email verification sent to admin user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $request->role,
                    'created_by' => auth()->id()
                ]);

                session()->flash('swal', [
                    'icon' => 'success',
                    'title' => '¡Usuario creado!',
                    'text' => 'El usuario administrativo ha sido creado. Se ha enviado un correo de verificación a ' . $user->email,
                    'timeout' => 5000
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send admin email verification', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);

                session()->flash('swal', [
                    'icon' => 'warning',
                    'title' => 'Usuario creado con advertencia',
                    'text' => 'El usuario fue creado pero no se pudo enviar el correo de verificación. Verifica manualmente el email.',
                    'timeout' => 5000
                ]);
            }
        } else {
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Usuario creado!',
                'text' => 'El usuario administrativo ha sido creado correctamente (modo desarrollo).',
                'timeout' => 3000
            ]);
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('manage-users');

        $user->load('roles', 'orders');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('manage-users');

        $roles = UserRole::toArray();
        $documentTypes = TypeOfDocuments::toArray();
        $currentRole = $user->roles->first()?->name;

        return view('admin.users.edit', compact('user', 'roles', 'documentTypes', 'currentRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'document_type' => ['required', 'integer', new Enum(TypeOfDocuments::class)],
            'document_number' => ['required', 'string', 'max:255', 'unique:users,document_number,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:10', 'unique:users,phone,' . $user->id],
            'password' => ['nullable', Password::default(), 'confirmed'],
            'role' => ['required', 'string', new Enum(UserRole::class)],
        ], [
            'email.unique' => 'Ya existe un usuario con este correo electrónico.',
            'document_number.unique' => 'Ya existe un usuario con este número de documento.',
            'phone.unique' => 'Ya existe un usuario con este número de teléfono.',
        ]);

        // Validación de seguridad: No permitir cambiar email a uno existente
        if ($request->email !== $user->email) {
            $existingUser = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
            if ($existingUser) {
                return back()->withErrors([
                    'email' => 'Ya existe una cuenta con este correo electrónico.'
                ])->withInput();
            }
        }

        $updateData = [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // Solo actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Actualizar rol
        $user->syncRoles([$request->role]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Usuario actualizado!',
            'text' => 'El usuario ha sido actualizado correctamente.',
            'timeout' => 3000
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('manage-users');

        // Prevenir eliminar el usuario actual
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propia cuenta.'
            ]);
        }

        // Prevenir eliminar usuarios con órdenes
        if ($user->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un usuario que tiene órdenes asociadas.'
            ]);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.'
        ]);
    }
}
