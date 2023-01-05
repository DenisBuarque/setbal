<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Forum;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Inscription;
use App\Models\Comment;
use App\Models\Opinion;

class ForumController extends Controller
{
    private $forum;
    private $course;
    private $subject;
    private $inscription;
    private $comment;
    private $opinion;

    public function __construct(Forum $forum, Course $course, Subject $subject, Inscription $inscription, Comment $comment, Opinion $opinion) 
    {
        $this->forum = $forum;
        $this->course = $course;
        $this->subject = $subject;
        $this->inscription = $inscription;
        $this->comment = $comment;
        $this->opinion = $opinion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->forum->query();

        $search = $request->search;
        $search2 = $request->subject;

        if (isset($search2)) {
            $query->where('subject_id', $search2);
        }

        if (!empty($search)) {
            $columns = ['title'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->title . '%');
            }
        }

        $inscriptions = $this->inscription->all();
        $subjects = $this->subject->all();

        $forums = $query->orderBY('id','DESC')->paginate(10);
        return view('admin.forums.index', [
            'forums' => $forums,
            'subjects' => $subjects,
            'inscriptions' => $inscriptions,
            'search' => $search,
            'search2' => $search2
        ]);
    }

    public function list ($id) 
    {
        $subjects = $this->subject->where('status','sim')->where('course_id', '=', $id)->get();
        return view('admin.forums.search',['subjects' => $subjects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = $this->course->where('status','sim')->get();
        $forums = $this->forum->all();
        return view('admin.forums.create',['forums' => $forums, 'courses' => $courses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'course_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
        ])->validate();

        if($this->forum->create($data)) {
            return redirect('admin/forums')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/forums/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    public function comments (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'user_id' => 'required',
            'forum_id' => 'required',
            'comment' => 'required|string|min:10|max:5000',
        ])->validate();

        if($this->comment->create($data)) {
            return redirect('admin/forums/show/' . $data['forum_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/forums')->with('error', 'Erro ao inserir o registro!');
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
            return redirect('admin/forums/show/' . $data['forum_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/forums')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $forum = $this->forum->find($id);

        $comments = $this->comment->where('forum_id',$id)->get();
        $opinions = $this->opinion->all();

        $inscriptions = $this->inscription->where('subject_id', $forum->subject_id)->where('status','pago')->get();

        if ($forum) {
            return view('admin.forums.show',[
                'forum' => $forum, 
                'inscriptions' => $inscriptions, 
                'comments' => $comments, 
                'opinions' => $opinions
            ]);
        }

        return redirect('admin/forums')->with('alert', 'Registro não encontrado!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $courses = $this->course->all();
        $subjects = $this->subject->where('course_id', $id)->get();
        
        $forum = $this->forum->find($id);
        if ($forum) {
            return view('admin.forums.edit',['forum' => $forum, 'courses' => $courses, 'subjects' => $subjects]);
        }

        return redirect('admin/forums')->with('alert', 'Registro não encontrado!');
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
        $data = $request->all();
        $forum = $this->forum->find($id);

        if (!$forum) {
            return redirect('admin/forums')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ])->validate();

        if($forum->update($data)) {
            return redirect('admin/forums')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/forums')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->forum->find($id);

        if($data->delete()) {
            return redirect('admin/forums')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/forums')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
