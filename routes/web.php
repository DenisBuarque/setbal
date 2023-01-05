<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PoloController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\MuralController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\OpenQuestionController;
use App\Http\Controllers\MultipleQuestionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ContractController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/classroom/{slug}', [App\Http\Controllers\HomeController::class, 'classroom'])->name('classroom');
Route::get('/classroom/discipline/{id}', [App\Http\Controllers\HomeController::class, 'discipline'])->name('classroom.discipline');
Route::get('/classroom/discipline/module/{id}', [App\Http\Controllers\HomeController::class, 'mod'])->name('classroom.discipline.module');
Route::get('/classroom/forum/discipline/{id}', [App\Http\Controllers\HomeController::class, 'forum'])->name('classroom.forum.discipline');
Route::post('/classroom/forum/comment', [App\Http\Controllers\HomeController::class, 'comments'])->name('classroom.forum.comment');
Route::post('/classroom/forum/opinion', [App\Http\Controllers\HomeController::class, 'opinions'])->name('classroom.forum.opinion');
Route::get('/classroom/avaluation/{id}', [App\Http\Controllers\HomeController::class, 'avaluation'])->name('classroom.avaluation');
Route::get('/classroom/avaluation/multiple/{id}', [App\Http\Controllers\HomeController::class, 'multiple'])->name('classroom.avaluation.multiple');
Route::post('/classroom/avaluation/multiple/response', [App\Http\Controllers\HomeController::class, 'multiple_response'])->name('classroom.avaluation.multiple.response');
Route::get('/classroom/avaluation/open/{id}', [App\Http\Controllers\HomeController::class, 'open_questions'])->name('classroom.avaluation.open');
Route::post('/classroom/avaluation/open/response', [App\Http\Controllers\HomeController::class, 'open_response'])->name('classroom.avaluation.open.response');
Route::get('/classroom/avaluation/job/{id}', [App\Http\Controllers\HomeController::class, 'job'])->name('classroom.avaluation.job');
Route::post('/classroom/avaluation/store/job', [App\Http\Controllers\HomeController::class, 'store_job'])->name('classroom.avaluation.store.job');

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/user/create', [UserController::class, 'create'])->name('admin.user.create');
Route::post('/admin/user/store', [UserController::class, 'store'])->name('admin.user.store');
Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
Route::put('/admin/user/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
Route::get('/admin/user/destroy/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

//CURSOS
Route::get('/admin/courses', [CourseController::class, 'index'])->name('admin.courses.index');
Route::get('/admin/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
Route::post('/admin/courses/store', [CourseController::class, 'store'])->name('admin.courses.store');
Route::get('/admin/courses/edit/{id}', [CourseController::class, 'edit'])->name('admin.courses.edit');
Route::put('/admin/courses/update/{id}', [CourseController::class, 'update'])->name('admin.courses.update');
Route::get('/admin/courses/destroy/{id}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

//DISCIPLINAS
Route::get('/admin/subjects', [SubjectController::class, 'index'])->name('admin.subjects.index');
Route::get('/admin/subjects/create', [SubjectController::class, 'create'])->name('admin.subjects.create');
Route::post('/admin/subjects/store', [SubjectController::class, 'store'])->name('admin.subjects.store');
Route::get('/admin/subjects/edit/{id}', [SubjectController::class, 'edit'])->name('admin.subjects.edit');
Route::put('/admin/subjects/update/{id}', [SubjectController::class, 'update'])->name('admin.subjects.update');
Route::get('/admin/subjects/destroy/{id}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');

// MODULOS
Route::get('/admin/subject/{id}/modules', [ModuleController::class, 'index'])->name('admin.modules.index');
Route::post('/admin/subject/modules', [ModuleController::class, 'search'])->name('admin.modules.search');
Route::get('/admin/subject/{id}/module/create', [ModuleController::class, 'create'])->name('admin.modules.create');
Route::post('/admin/modules/store', [ModuleController::class, 'store'])->name('admin.modules.store');
Route::get('/admin/modules/edit/{id}', [ModuleController::class, 'edit'])->name('admin.modules.edit');
Route::put('/admin/modules/update/{id}', [ModuleController::class, 'update'])->name('admin.modules.update');
Route::get('/admin/modules/destroy/{id}', [ModuleController::class, 'destroy'])->name('admin.modules.destroy');
// FILES MODULOS
Route::get('/admin/module/{id}/file/create', [ModuleController::class, 'fileCreate'])->name('admin.modules.fileCreate');
Route::post('/admin/module/file/store', [ModuleController::class, 'fileStore'])->name('admin.modules.fileStore');
Route::get('/admin/module/file/{id}/edit', [ModuleController::class, 'fileEdit'])->name('admin.modules.fileEdit');
// VIDEOS MODULOS
Route::get('/admin/module/{id}/video/create', [ModuleController::class, 'videoCreate'])->name('admin.modules.videoCreate');
Route::post('/admin/module/video/store', [ModuleController::class, 'videoStore'])->name('admin.modules.videoStore');
Route::get('/admin/module/video/{id}/edit', [ModuleController::class, 'videoEdit'])->name('admin.modules.videoEdit');
Route::put('/admin/module/video/update/{id}', [ModuleController::class, 'update'])->name('admin.modules.videoUpdate');

//POLOS
Route::get('/admin/polos', [PoloController::class, 'index'])->name('admin.polos.index');
Route::get('/admin/polos/create', [PoloController::class, 'create'])->name('admin.polos.create');
Route::post('/admin/polos/store', [PoloController::class, 'store'])->name('admin.polos.store');
Route::get('/admin/polos/edit/{id}', [PoloController::class, 'edit'])->name('admin.polos.edit');
Route::put('/admin/polos/update/{id}', [PoloController::class, 'update'])->name('admin.polos.update');
Route::delete('/admin/polos/destroy/{id}', [PoloController::class, 'destroy'])->name('admin.polos.destroy');

//ALUNOS
Route::get('/admin/students', [StudentController::class, 'index'])->name('admin.students.index');
Route::get('/admin/students/show/{id}', [StudentController::class, 'show'])->name('admin.students.show');
Route::get('/admin/students/create', [StudentController::class, 'create'])->name('admin.students.create');
Route::post('/admin/students/store', [StudentController::class, 'store'])->name('admin.students.store');
Route::get('/admin/students/edit/{id}', [StudentController::class, 'edit'])->name('admin.students.edit');
Route::put('/admin/students/update/{id}', [StudentController::class, 'update'])->name('admin.students.update');
Route::get('/admin/students/destroy/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

//PROFESSORES
Route::get('/admin/teachers', [TeacherController::class, 'index'])->name('admin.teachers.index');
Route::get('/admin/teachers/create', [TeacherController::class, 'create'])->name('admin.teachers.create');
Route::get('/admin/teachers/list/{id}', [TeacherController::class, 'list'])->name('admin.teachers.list');
Route::post('/admin/teachers/store', [TeacherController::class, 'store'])->name('admin.teachers.store');
Route::get('/admin/teachers/edit/{id}', [TeacherController::class, 'edit'])->name('admin.teachers.edit');
Route::put('/admin/teachers/update/{id}', [TeacherController::class, 'update'])->name('admin.teachers.update');
Route::get('/admin/teachers/destroy/{id}', [TeacherController::class, 'destroy'])->name('admin.teachers.destroy');

// MATRICULAS
Route::get('/admin/registrations', [RegistrationController::class, 'index'])->name('admin.registrations.index');
Route::get('/admin/registrations/create', [RegistrationController::class, 'create'])->name('admin.registrations.create');
Route::post('/admin/registrations/store', [RegistrationController::class, 'store'])->name('admin.registrations.store');
Route::get('/admin/registrations/edit/{id}', [RegistrationController::class, 'edit'])->name('admin.registrations.edit');
Route::put('/admin/registrations/update/{id}', [RegistrationController::class, 'update'])->name('admin.registrations.update');
Route::get('/admin/registrations/destroy/{id}', [RegistrationController::class, 'destroy'])->name('admin.registrations.destroy');

//INSCRIÇÕES
Route::get('/admin/inscriptions', [InscriptionController::class, 'index'])->name('admin.inscriptions.index');
Route::get('/admin/inscriptions/create', [InscriptionController::class, 'create'])->name('admin.inscriptions.create');
Route::get('/admin/inscriptions/list/{id}', [InscriptionController::class, 'list'])->name('admin.inscriptions.list');
Route::post('/admin/inscriptions/store', [InscriptionController::class, 'store'])->name('admin.inscriptions.store');
Route::get('/admin/inscriptions/edit/{id}', [InscriptionController::class, 'edit'])->name('admin.inscriptions.edit');
Route::put('/admin/inscriptions/update/{id}', [InscriptionController::class, 'update'])->name('admin.inscriptions.update');
Route::get('/admin/inscriptions/destroy/{id}', [InscriptionController::class, 'destroy'])->name('admin.inscriptions.destroy');

// MURAL
Route::get('/admin/murals', [MuralController::class, 'index'])->name('admin.murals.index');
Route::get('/admin/murals/create', [MuralController::class, 'create'])->name('admin.murals.create');
Route::get('/admin/murals/list/{id}', [MuralController::class, 'list'])->name('admin.murals.list');
Route::post('/admin/murals/store', [MuralController::class, 'store'])->name('admin.murals.store');
Route::get('/admin/murals/edit/{id}', [MuralController::class, 'edit'])->name('admin.murals.edit');
Route::put('/admin/murals/update/{id}', [MuralController::class, 'update'])->name('admin.murals.update');
Route::get('/admin/murals/destroy/{id}', [MuralController::class, 'destroy'])->name('admin.murals.destroy');

// FORUM
Route::get('/admin/forums', [ForumController::class, 'index'])->name('admin.forums.index');
Route::get('/admin/forums/create', [ForumController::class, 'create'])->name('admin.forums.create');
Route::get('/admin/forums/show/{id}', [ForumController::class, 'show'])->name('admin.forums.show');
Route::get('/admin/forums/list/{id}', [ForumController::class, 'list'])->name('admin.forums.list');
Route::post('/admin/forums/store', [ForumController::class, 'store'])->name('admin.forums.store');
Route::post('/admin/forums/comments', [ForumController::class, 'comments'])->name('admin.forums.comments');
Route::post('/admin/forums/opinions', [ForumController::class, 'opinions'])->name('admin.forums.opinions');
Route::get('/admin/forums/edit/{id}', [ForumController::class, 'edit'])->name('admin.forums.edit');
Route::put('/admin/forums/update/{id}', [ForumController::class, 'update'])->name('admin.forums.update');
Route::get('/admin/forums/destroy/{id}', [ForumController::class, 'destroy'])->name('admin.forums.destroy');

// OPEN QUESTIONS
Route::get('/admin/openquestions', [OpenQuestionController::class, 'index'])->name('admin.openquestions.index');
Route::get('/admin/openquestions/create', [OpenQuestionController::class, 'create'])->name('admin.openquestions.create');
Route::get('/admin/openquestions/list/{id}', [OpenQuestionController::class, 'list'])->name('admin.openquestions.list');
Route::post('/admin/openquestions/store', [OpenQuestionController::class, 'store'])->name('admin.openquestions.store');
Route::get('/admin/openquestions/edit/{id}', [OpenQuestionController::class, 'edit'])->name('admin.openquestions.edit');
Route::put('/admin/openquestions/update/{id}', [OpenQuestionController::class, 'update'])->name('admin.openquestions.update');
Route::get('/admin/openquestions/destroy/{id}', [OpenQuestionController::class, 'destroy'])->name('admin.openquestions.destroy');

// MULTIPLE QUESTIONS
Route::get('/admin/multiplequestions', [MultipleQuestionController::class, 'index'])->name('admin.multiplequestions.index');
Route::get('/admin/multiplequestions/create', [MultipleQuestionController::class, 'create'])->name('admin.multiplequestions.create');
Route::get('/admin/multiplequestions/list/{id}', [MultipleQuestionController::class, 'list'])->name('admin.multiplequestions.list');
Route::post('/admin/multiplequestions/store', [MultipleQuestionController::class, 'store'])->name('admin.multiplequestions.store');
Route::get('/admin/multiplequestions/edit/{id}', [MultipleQuestionController::class, 'edit'])->name('admin.multiplequestions.edit');
Route::put('/admin/multiplequestions/update/{id}', [MultipleQuestionController::class, 'update'])->name('admin.multiplequestions.update');
Route::get('/admin/multiplequestions/destroy/{id}', [MultipleQuestionController::class, 'destroy'])->name('admin.multiplequestions.destroy');

// TICKET
Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
Route::get('/admin/tickets/create', [TicketController::class, 'create'])->name('admin.tickets.create');
Route::post('/admin/tickets/store', [TicketController::class, 'store'])->name('admin.tickets.store');
Route::get('/admin/tickets/edit/{id}', [TicketController::class, 'edit'])->name('admin.tickets.edit');
Route::put('/admin/tickets/update/{id}', [TicketController::class, 'update'])->name('admin.tickets.update');
Route::get('/admin/tickets/destroy/{id}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');

//CONTRACT
Route::get('/admin/contracts', [ContractController::class, 'index'])->name('admin.contracts.index');
Route::get('/admin/contracts/create', [ContractController::class, 'create'])->name('admin.contracts.create');
Route::post('/admin/contracts/store', [ContractController::class, 'store'])->name('admin.contracts.store');
Route::get('/admin/contracts/edit/{id}', [ContractController::class, 'edit'])->name('admin.contracts.edit');
Route::put('/admin/contracts/update/{id}', [ContractController::class, 'update'])->name('admin.contracts.update');
Route::get('/admin/contracts/destroy/{id}', [ContractController::class, 'destroy'])->name('admin.contracts.destroy');