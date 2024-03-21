<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherSubject;
use App\Http\Controllers\TeacherTitle;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TeacherSiteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\reporteController;
use App\Http\Controllers\StudentSiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[LoginController::class, 'welcome'])->name('welcome');

//Rutas relacionados con el controlador de docentes (TeacherController)
Route::prefix('teacher')->group(function(){
    route::get('/index',[TeacherController::class,'index'])->name('teachers.index');
    route::get('/create',[TeacherController::class,'create'])->name('teachers.create');    
    route::post('/add',[TeacherController::class,'store'])->name('teachers.add');    
    route::get('/getTeacher/{id}',[TeacherController::class, 'getTeacherInfo'])->name('teachers.getInfo');
    route::put('/update',[TeacherController::class, 'update'])->name('teacher.update');
    route::get('/show/{id}',[TeacherController::class,'show'])->name('teachers.showInfo');
    route::get('/edit/{id}',[TeacherController::class,'edit'])->name('teachers.edit');
    route::post('/addSubject',[TeacherController::class,'addTeacherSubject'])->name('subject.add');
    route::post('/addTitle',[TeacherController::class,'addTeacherTitle'])->name('title.add');
    route::delete('/removeSubject',[TeacherController::class,'deleteTeacherSubject'])->name('subjectT.delete');
    route::delete('/removeTitle',[TeacherController::class,'deleteTeacherTitle'])->name('title.delete');
    route::delete('/delete',[TeacherController::class,'destroy'])->name('teacher.delete');
    route::get('/restoreView',[TeacherController::class,'restoreView'])->name('teacher.restoreView');
    route::put('/restore',[TeacherController::class, 'restore'])->name('teacher.restore');
    


});

//Rutas relacionadas con el controlador de estudiante (StudentController)
Route::prefix('student')->group(function(){
    route::get('/create',[StudentController::class, 'create'])->name('student.create');
    route::post('/add',[StudentController::class, 'store'])->name('student.store');
    route::get('/index',[StudentController::class, 'index'])->name('student.index');
    route::get('/show/{id}',[StudentController::class, 'show'])->name('student.showInfo');
    route::get('/getStudent/{id}',[StudentController::class, 'getStudentInfo'])->name('student.getInfo');
    route::put('/update',[StudentController::class, 'update'])->name('student.update');
    route::delete('/delete',[StudentController::class, 'destroy'])->name('student.delete');
    route::get('/restoreView',[StudentController::class, 'restoreView'])->name('student.restoreView');
    route::put('/restore',[StudentController::class, 'restore'])->name('student.restore');
    route::get('/rejected',[StudentController::class, 'rejected'])->name('student.rejected');
    route::get('/rejectedInfo/{id}',[StudentController::class, 'getRejectedCandidateInfo'])->name('student.rejectedInfo');
    route::delete('/deleteCandidate',[StudentController::class, 'deleteCandidate'])->name('student.deleteCandidate');
    route::put('/acceptCandidate',[StudentController::class, 'acceptCandidate'])->name('student.acceptCandidate');
    route::get('/record/{id}',[StudentController::class, 'showRecord'])->name('student.record');
    route::get('/addMateria',[StudentController::class, 'storeMateria'])->name('student.storeMateria');
    route::post('/registroMaterias/{studentID}/{subjectID}/',[StudentController::class, 'registroMateria'])->name('student.registroMateria');
    route::get('/showFailed',[StudentController::class, 'showFailedExtra'])->name('student.showFailedExtra');
    route::get('/showFailedInfo/{id}',[StudentController::class, 'showFailedExtraInfo'])->name('student.showFailedExtraInfo');
    route::post('/storeActivityExtra',[StudentController::class, 'storeActivityExtra'])->name('student.storeActivityExtra');
    route::get('/extraGrade/{id}',[StudentController::class,'storeGradeExtra'])->name('student.storeGradeExtra');
    route::put('/storeExtraGrade',[StudentController::class,'storeGradeE'])->name('student.storeGradeE');

    
});

//Rutas relacionadas con el controlador de materias (SubjectController)
Route::prefix('subject')->group(function(){
    route::get('/create',[SubjectController::class, 'create'])->name('subject.create');
    route::post('/add',[SubjectController::class, 'store'])->name('subject.store');
    route::get('/index',[SubjectController::class, 'index'])->name('subject.index');
    route::get('/show/{id}',[SubjectController::class, 'show'])->name('subject.showInfo');
    route::get('/getDuration/{id}',[SubjectController::class, 'getPhaseDuration'])->name('subject.getDuration');
    route::put('/update',[SubjectController::class, 'update'])->name('subject.update');
    // route::delete('/delete',[SubjectController::class, 'deleteTeacherSubject'])->name('subject.delete');
    route::get('/restoreView',[SubjectController::class, 'restoreView'])->name('subject.restoreView');
    route::put('/restore',[SubjectController::class, 'restore'])->name('subject.restore');
    route::get('/getMateria/{id}',[SubjectController::class, 'getMateria'])->name('subject.getMateria');
    route::get('/indexEliminadas',[SubjectController::class, 'indexEliminadas'])->name('subject.indexEliminadas');
    route::put('/restore',[SubjectController::class, 'restore'])->name('subject.restore');
    route::delete('/restore',[SubjectController::class, 'destroy'])->name('subject.destroy');
});

//Rutas relacionadas con el controlador de ciclos (PeriodController)
Route::prefix('period')->group(function(){
    route::get('/create',[PeriodController::class, 'create'])->name('period.create');   
    route::post('/store',[PeriodController::class, 'store'])->name('period.store');   
    route::get('/information/{id}',[PeriodController::class, 'show'])->name('period.information');   
    route::put('/end',[PeriodController::class, 'endPeriod'])->name('period.end');   
    route::put('/update',[PeriodController::class, 'update'])->name('period.update');   
    route::put('/start',[PeriodController::class, 'startPeriod'])->name('period.start');   
    route::get('/groups/{id}',[PeriodController::class, 'showGroups'])->name('period.groups');   
    route::get('/groups/{id}',[PeriodController::class, 'showGroups'])->name('period.groups');   
});

//Rutas relacionadas con el controlador de materias (SubjectController)
Route::prefix('group')->group(function(){
    route::get('/information/{id}',[GroupController::class, 'show'])->name('group.information');   
    route::get('/create',[GroupController::class, 'create'])->name('group.create');  
    route::post('/store',[GroupController::class, 'store'])->name('group.store');  
    route::get('/teacherSubject/{id}',[GroupController::class, 'getTeacherSubject']);
    route::post('/storeGroupStudent',[GroupController::class, 'storeStudentsGroup'])->name('group.storeStudents');
    route::post('/storeStudents',[GroupController::class, 'storeStudentsGroup'])->name('group.storeStudentsGroup');
    route::get('/index',[GroupController::class, 'groupControl'])->name('group.index');
    route::get('/addStudents/{id}',[GroupController::class, 'addStudentsGroup'])->name('group.addStudents');    
    route::get('/finalizedGroups',[GroupController::class, 'showFinalizedGroups'])->name('group.finalized');    
    route::post('/deleteStudent',[GroupController::class, 'deleteStudent'])->name('group.deleteStudent');            
    route::delete('/deleteGroup',[GroupController::class, 'deleteGroup'])->name('group.deleteGroup');            
});

//Rutas relacionadas con el controlador de evaluaciones (EvaluacionController)
Route::prefix('evaluacion')->group(function(){
    route::get('/formulario/{id}',[EvaluacionController::class, 'formulario'])->name('evaluacion.formulario');
    route::post('/store/{id}',[EvaluacionController::class, 'store'])->name('evaluacion.store');
    route::get('/getEvaluacion/{id}',[EvaluacionController::class, 'getEvaluacionInfo'])->name('evaluacion.getInfo');
    route::put('/update',[EvaluacionController::class, 'update'])->name('evaluacion.update');
    route::delete('/delete',[EvaluacionController::class, 'destroy'])->name('evaluacion.delete');
});

//Rutas relacionados con el controlador de usuarios (UsuarioController)
Route::prefix('user')->group(function(){
    route::get('/index',[UsuarioController::class,'index'])->name('users.index');
    route::get('/getUser/{id}',[UsuarioController::class, 'getUser'])->name('user.getUser');
    route::delete('/delete',[UsuarioController::class, 'destroy'])->name('user.delete');
    route::get('/solicitudes',[UsuarioController::class,'solicitudes'])->name('users.solicitudes');
    route::put('/update',[UsuarioController::class, 'update'])->name('users.update');
    route::get('/getSolicitud/{id}',[UsuarioController::class, 'getSolicitud'])->name('user.getSolicitud');
    route::get('/cambiarContraFormulario',[UsuarioController::class,'formContra'])->name('users.formContra');
    route::put('/cambiarContra',[UsuarioController::class, 'cambiarContra'])->name('user.cambiarContra');
});



//Rutas relacionadas con el controlador de login (LoginController)
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::get('/loginView', [LoginController::class, 'showLogin'])->name('showLogin');
Route::post('/firsAdmin', [LoginController::class, 'storeFirstAdmin'])->name('storeFirstAdmin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/recuperarView', [LoginController::class, 'recuperarView'])->name('recuperarView');
Route::post('/recuperarContra', [LoginController::class, 'recuperarContra'])->name('recuperarContra');

//Rutas relacionadas con el controlador de sitio del docente (TeacherSiteController)
Route::prefix('teacherSite')->group(function(){
    route::get('/index',[TeacherSiteController::class,'index'])->name('teacherSite.index');    
    route::get('/groupInformation/{id}',[TeacherSiteController::class,'showGroupInformation'])->name('teacherSite.groupInformation');    
    route::get('/showEvaluations/{id}',[TeacherSiteController::class,'showEvaluations'])->name('teacherSite.showEvaluations'); 
    route::get('/gestionEvaluaciones/{id}',[TeacherSiteController::class,'gestionEvaluaciones'])->name('teacherSite.gestionEvaluaciones');
    route::get('/gradesAssigment/{id}',[TeacherSiteController::class,'showGradesAssigment'])->name('teacherSite.gradesAssigment');
    route::post('/storeGrades',[TeacherSiteController::class,'storeGrades'])->name('teacherSite.storeGrades');      
    route::get('/updateGradesView/{id}',[TeacherSiteController::class,'updateGradesView'])->name('teacherSite.updateGradesView');
    route::get('getNota/{id}',[TeacherSiteController::class,'getNota'])->name('teacherSite.getNota');
    route::put('/updateGrade',[TeacherSiteController::class,'updateGrade'])->name('teacherSite.updateGrade');
    route::post('/endGroup',[TeacherSiteController::class,'endGroup'])->name('teacherSite.endGroup');
    route::get('/showFailed',[TeacherSiteController::class,'showFailedStudents'])->name('teacherSite.showFailed');
    route::get('/showFailedInfo/{id}',[TeacherSiteController::class,'showFailedStudentsInfo'])->name('teacherSite.showFailedInfo');
    route::post('/storeActivity',[TeacherSiteController::class,'storeActivity'])->name('teacherSite.storeActivity');
    route::get('/extraGrade/{id}',[TeacherSiteController::class,'storeGradeExtra'])->name('teacherSite.storeGradeExtra');
    route::put('/storeExtraGrade',[TeacherSiteController::class,'storeGradeE'])->name('teacherSite.storeGradeE');
    route::get('/miPerfil',[TeacherSiteController::class,'miPerfil'])->name('teacherSite.miPerfil');
    route::put('/updateInfor',[TeacherSiteController::class,'updateInfor'])->name('teacherSite.updateInfor');
});

//Rutas relacionadas con el controlador de sitio del docente (TeacherSiteController)
Route::prefix('studentSite')->group(function(){
    route::get('/index',[StudentSiteController::class,'index'])->name('studentSite.index');     
    route::get('/subjectGrades/{id}',[StudentSiteController::class,'showSubjectGrade'])->name('studentSite.showSubjectGrade');     
    route::get('/record',[StudentSiteController::class, 'showRecord'])->name('studentSite.record');
    route::get('/miPerfil',[StudentSiteController::class,'miPerfil'])->name('studentSite.miPerfil');
    route::put('/updateInfor',[StudentSiteController::class,'updateInfor'])->name('studentSite.updateInfor');
});

//Rutas relacionados con el controlador de administradores (AdminController)
Route::prefix('admin')->group(function(){
    route::get('/create',[AdminController::class,'create'])->name('admin.create');
    route::post('/add',[AdminController::class,'store'])->name('admin.add');
    route::get('/index',[AdminController::class,'index'])->name('admin.index');
    route::get('/getAdmin/{id}',[AdminController::class, 'getAdmin'])->name('user.getAdmin');
    route::put('/update',[AdminController::class, 'update'])->name('admin.update');
    route::delete('/delete',[AdminController::class, 'destroy'])->name('admin.delete');
    route::get('/indexE',[AdminController::class,'indexE'])->name('admin.indexE');
    route::put('/restore',[AdminController::class, 'restore'])->name('admin.restore');
});

Route::prefix('pdf')->group(function(){
    route::get('/cuadro_Notas/{id}',[reporteController::class,'pdfGrupal'])->name('pdf.cuadroNotas');
    route::post('/notasIndividuales/{id}',[reporteController::class,'pdfIndividual'])->name('pdf.actaCalificacion');
     // route::get('/cuadro_Notas',[reporteController::class,'pdfGrupal'])->name('pdf.cuadroNotas');
 });