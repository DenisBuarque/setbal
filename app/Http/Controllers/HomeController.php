<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Mural;
use App\Models\Module;
use App\Models\Forum;
use App\Models\Inscription;
use App\Models\Comment;
use App\Models\Opinion;
use App\Models\MultipleQuestion;
use App\Models\MultipleResponse;
use App\Models\OpenQuestion;
use App\Models\OpenResponse;
use App\Models\Job;

class HomeController extends Controller
{
    private $course;
    private $subject;
    private $mural;
    private $module;
    private $forum;
    private $inscription;
    private $comment;
    private $opinion;
    private $multipleQuestion;
    private $multipleResponse;
    private $openQuestion;
    private $openResponse;
    private $job;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Course $course, 
        Subject $subject, 
        Mural $mural, 
        Module $module, 
        Forum $forum, 
        Inscription $inscription, 
        Comment $comment, 
        Opinion $opinion, 
        MultipleQuestion $multipleQuestion, 
        MultipleResponse $multipleResponse, 
        OpenQuestion $openQuestion,
        OpenResponse $openResponse,
        Job $job
        )
    {
        $this->middleware('auth');

        $this->course = $course;
        $this->subject = $subject;
        $this->mural = $mural;
        $this->module = $module;
        $this->forum = $forum;
        $this->inscription = $inscription;
        $this->comment = $comment;
        $this->opinion = $opinion;
        $this->multipleQuestion = $multipleQuestion;
        $this->multipleResponse = $multipleResponse;
        $this->openQuestion = $openQuestion;
        $this->openResponse = $openResponse;
        $this->job = $job;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $courses = $this->course->where('type','ead')->get();
        $murals = $this->mural->all();
        return view('home',['courses' => $courses, 'murals' => $murals]);
    }

    public static function getNotaMultiple ($course, $subject, $user) 
    {
        $data = DB::table('multiple_responses')
                    ->where('multiple_responses.course_id', $course)
                    ->where('multiple_responses.subject_id', $subject)
                    ->where('multiple_responses.user_id',$user)
                    ->join('multiple_questions', 'multiple_responses.multiple_question_id', '=', 'multiple_questions.id')
                    ->get();


        if ($data->count() == 0) {
            return 0;
        } else {
            $nota = 0;
            foreach ($data as $key => $response) {
                if ($response->option == $response->gabarito) {
                    $nota++;
                }
            }
            return $nota;
        }
    }

    public static function getNotaOpen ($course, $subject, $user) 
    {
        $data = DB::table('open_responses')
                    ->where('open_responses.course_id', $course)
                    ->where('open_responses.subject_id', $subject)
                    ->where('open_responses.user_id',$user)
                    ->join('open_questions', 'open_responses.open_question_id', '=', 'open_questions.id')
                    ->get();


        if ($data->count() == 0) {
            return 0;
        } else {
            $nota = 0;
            foreach ($data as $response) {
                $nota += $response->nota;
            }
            return $nota;
        }
    }

    public static function getNotaJob ($course, $subject, $user) 
    {
        $data = DB::table('jobs')
                    ->where('course_id', $course)
                    ->where('subject_id', $subject)
                    ->where('user_id',$user)
                    ->first();

        if (!$data) {
            return 0;
        } else {
            return $data->nota;
        }
    }

    public function classroom ($slug) 
    {
        $course = $this->course->where('slug',$slug)->first();
        if (!$course) {
            return redirect('home')->with('error', 'Curso não encontrado.');
        }

        $murals = $this->mural->all();

        $multipleResponses = $this->multipleResponse->where('course_id', $course->id)->get();

        $openResponses = $this->openResponse->where('course_id', $course->id)->get();
        $jobs = $this->job->where('course_id', $course->id)->where('user_id', Auth::id())->get();

        $subjects = $this->subject->where('course_id', $course->id)->get();
        if($subjects) {
            return view('classroom', [
                'course' => $course, 
                'subjects' => $subjects, 
                'murals' => $murals,
                'multipleResponses' => $multipleResponses,
                'openResponses' => $openResponses,
                'jobs' => $jobs
            ]);
        } else {
            return redirect('home')->with('error', 'Curso não encontrado.');
        }
    }


    public function discipline ($id) {

        $discipline = $this->subject->find($id);
        if ($discipline) {

            $modules = $this->module->where('subject_id', $id)->where('category','module')->get();
            $files = $this->module->where('subject_id', $id)->where('category','file')->get();
            $videos = $this->module->where('subject_id', $id)->where('category','movie')->get();

            return view('discipline',['discipline' => $discipline, 'modules' => $modules, 'files' => $files, 'videos' => $videos]);
        }

        return redirect('home')->with('error', 'Acesso indisponível.');

    }

    public function mod($id) 
    {
        $module = $this->module->find($id);
        return view('module',['module' => $module]);
    }

    public function forum ($id) 
    {
        $forum = $this->forum->where('subject_id', $id)->first();
        if (!$forum) {
            return redirect()->route('home');
        }

        $subject = $this->subject->where('id', $id)->first();
        $comments = $this->comment->where('forum_id', $forum->id)->get();
        $opinions = $this->opinion->all();

        $inscriptions = $this->inscription->where('subject_id', $id)->where('status','pago')->get();

        if ($forum) {
            return view('forum',[
                'forum' => $forum, 
                'inscriptions' => $inscriptions, 
                'comments' => $comments, 
                'opinions' => $opinions,
                'subject' => $subject
            ]);
        }
    }

    public function comments (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'user_id' => 'required',
            'forum_id' => 'required',
            'comment' => 'required|string|min:2|max:5000',
        ])->validate();

        if($this->comment->create($data)) {
            return redirect('classroom/forum/discipline/' . $data['subject_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('classroom/forum/discipline' . $data['subject_id'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    public function opinions (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'user_id' => 'required',
            'comment_id' => 'required',
            'opinion' => 'required|string|min:1|max:1000',
        ])->validate();

        if($this->opinion->create($data)) {
            return redirect('classroom/forum/discipline/' . $data['subject_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('classroom/forum/discipline/' . $data['subject_id'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    public function avaluation ($id) {

        $murals = $this->mural->all();
        $subject = $this->subject->find($id);

        $multipleResponses = $this->multipleResponse->where('user_id', Auth::id())->where('subject_id', $id)->get();
        $openResponses = $this->openResponse->where('user_id', Auth::id())->where('subject_id', $id)->get();
        $jobs = $this->job->where('user_id', Auth::id())->where('subject_id', $id)->get();

        if($subject) {
            return view('avaluation', [
                'subject' => $subject, 
                'murals' => $murals, 
                'multipleResponses' => $multipleResponses, 
                'openResponses' => $openResponses,
                'jobs' => $jobs
            ]);

        } else {
            return redirect('home')->with('error', 'Curso não encontrado.');
        }
    }

    public function multiple ($id) {

        $subject = $this->subject->find($id);

        if (!$subject) {
            return redirect()->route('home');
        }

        $multipleQuestions = $this->multipleQuestion->where('subject_id', $id)->get();

        if($multipleQuestions) {
            return view('multipleQuestions', [
                'multipleQuestions' => $multipleQuestions, 
                'subject' => $subject
            ]);
        } else {
            return redirect('home')->with('error', 'Curso não encontrado.');
        }
    }

    public function multiple_response (Request $request) {

        $data = $request->all();

        for($i = 0; $i < 10; $i++) {

            $this->multipleResponse->create([
                'user_id' => $data['user_id'],
                'course_id' => $data['course_id'] ,
                'subject_id' => $data['subject_id'],
                'multiple_question_id' => $data['question'.$i],
                'option' => $data['option'.$i],
            ]);
        }

        return redirect('classroom/avaluation/' . $data['subject_id'])->with('success', 'Resposta enviada.');
    }

    public function open_questions ($id) {

        $subject = $this->subject->find($id);

        if (!$subject) {
            return redirect()->route('home');
        }

        $openQuestions = $this->openQuestion->where('subject_id', $id)->get();

        if($openQuestions) {
            return view('openQuestions', [
                'openQuestions' => $openQuestions, 
                'subject' => $subject
            ]);
        } else {
            return redirect('home')->with('error', 'Curso não encontrado.');
        }
    }

    public function open_response (Request $request) {

        $data = $request->all();
        for($i = 0; $i < 2; $i++) {
            $this->openResponse->create([
                'user_id' => $data['user_id'],
                'course_id' => $data['course_id'] ,
                'subject_id' => $data['subject_id'],
                'open_question_id' => $data['question'][$i],
                'comments' => $data['comments'][$i],
                'nota' => 0,
            ]);
        }

        return redirect('classroom/avaluation/' . $data['subject_id'])->with('success', 'Resposta enviada.');
    }

    public function job ($id) {

        $murals = $this->mural->all();
        $subject = $this->subject->find($id);

        if (!$subject) {
            return redirect()->route('home');
        }

        return view('job', ['subject' => $subject, 'murals' => $murals]);
    }

    public function store_job (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'user_id' => 'required',
            'course_id' => 'required',
            'subject_id' => 'required',
            'arquivo' => 'required|mimes:pdf,doc,docx',
        ])->validate();

        $data['nota'] = 0;

        if($request->hasFile('arquivo') && $request->file('arquivo')->isValid()){
            $file = $request->arquivo->store('jobs','public');
            $data['arquivo'] = $file;
        }

        $job = $this->job->create($data);
        if($job) {
            return redirect('classroom/avaluation/' . $data['subject_id'])->with('success', 'Registro inserido com sucesso!');
        }else{
            return redirect('classroom/avaluation/' . $data['subject_id'])->with('error', 'Erro ao inserido o registro!');
        }
    }
}
