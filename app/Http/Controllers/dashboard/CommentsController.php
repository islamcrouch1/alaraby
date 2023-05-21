<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:comments-read')->only('index', 'show');
        $this->middleware('permission:comments-create')->only('create', 'store');
        $this->middleware('permission:comments-update')->only('edit', 'update');
        $this->middleware('permission:comments-delete|comments-trash')->only('destroy', 'trashed');
        $this->middleware('permission:comments-restore')->only('restore');
    }

    public function index()
    {

        $comments = Comment::whenSearch(request()->search)
            ->latest()
            ->paginate(100);

        return view('dashboard.comments.index')->with('comments', $comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.comments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name_ar' => "required|string|max:255|unique:comments",
            'name_en' => "required|string|max:255|unique:comments",
        ]);


        $comment = Comment::create([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],

        ]);

        alertSuccess('comment created successfully', 'تم إضافة السنترال بنجاح');
        return redirect()->route('comments.index');
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
    public function edit($comment)
    {
        $comment = Comment::findOrFail($comment);
        return view('dashboard.comments.edit ')->with('comment', $comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'name_ar' => "required|string|max:255|unique:comments,name_ar," . $comment->id,
            'name_en' => "required|string|max:255|unique:comments,name_en," . $comment->id,

        ]);


        $comment->update([
            'name_ar' => $request['name_ar'],
            'name_en' => $request['name_en'],
        ]);



        alertSuccess('comment updated successfully', 'تم تعديل السنترال بنجاح');
        return redirect()->route('comments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($comment)
    {
        $comment = Comment::withTrashed()->where('id', $comment)->first();
        if ($comment->trashed() && auth()->user()->hasPermission('comments-delete')) {
            $comment->forceDelete();
            alertSuccess('comment deleted successfully', 'تم حذف السنترال بنجاح');
            return redirect()->route('comments.trashed');
        } elseif (!$comment->trashed() && auth()->user()->hasPermission('comments-trash') && checkCommentForTrash($comment)) {
            $comment->delete();
            alertSuccess('comment trashed successfully', 'تم حذف السنترال مؤقتا');
            return redirect()->route('comments.index');
        } else {
            alertError('Sorry, you do not have permission to perform this action, or the comment cannot be deleted at the moment', 'نأسف ليس لديك صلاحية للقيام بهذا الإجراء ، أو السنترال لا يمكن حذفه حاليا');
            return redirect()->back();
        }
    }

    public function trashed()
    {
        $comments = Comment::onlyTrashed()
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);
        return view('dashboard.comments.index', ['comments' => $comments]);
    }

    public function restore($comment, Request $request)
    {
        $comment = Comment::withTrashed()->where('id', $comment)->first()->restore();
        alertSuccess('comment restored successfully', 'تم استعادة السنترال بنجاح');
        return redirect()->route('comments.index', ['parent_id' => $request->parent_id]);
    }
}
