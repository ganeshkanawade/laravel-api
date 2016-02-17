<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Post as Post;
use Response;
use App\User;
use Input;
use JWTAuth;
use App\Repositories\PostRepository;

class PostsController extends Controller
{

    protected  $posts;


    public function __construct(PostRepository $posts){
        
        $this->middleware('jwt.auth');
        //$this->middleware('api.jwt', ['only' => ['index']]);

        $this->posts = $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_term = $request->input('search')?$request->input('search'):'';
        $limit = $request->input('limit')?$request->input('limit'):10;
        
        if ($search_term)
        {
            $posts = Post::orderBy('id', 'DESC')->where('post', 'LIKE', "%$search_term%")->orWhere('title', 'LIKE', "%$search_term%")->with(
                        array('User'=>function($query){
                            $query->select('id','name');
                        })
                    )->select('id', 'title','post', 'user_id')->paginate($limit); 

            $posts->appends(array(
                'search' => $search_term,
                'limit' => $limit
            ));
        }
        else
        {
            $posts = Post::orderBy('id', 'DESC')->with(
                        array('User'=>function($query){
                            $query->select('id','name');
                        })
                    )->select('id', 'title','post', 'user_id')->paginate($limit); 
 
            $posts->appends(array(            
                'limit' => $limit
            ));
        }
        
        if(!$posts){
            return response()->json([
                'error' => [
                    'message' => 'posts does not exist'
                ]
            ], 404);
        }
        
        return response()->json([
                            'data' => $this->transformCollection($posts)
                        ], 200)->setCallback($request->input('callback'));;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(! $request->title or ! $request->post or !$request->user_id){
            return Response::json([
                'error' => [
                    'message' => 'Insufficient data'
                ]
            ], 422);
        }
        $post = Post::create($request->all());
 
        return Response::json([
                'message' => 'Post Created Succesfully',
                'data' => $this->transform($post)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain,$id)
    {
        $post = Post::with(
            array('User'=>function($query){
                $query->select('id','name');
            })
            )->find($id);

        if(!$post){
            return Response::json([
                'error' => [
                    'message' => 'Post does not exist'
                ]
            ], 404);
        }
 
         // get previous joke id
        $previous = Post::where('id', '<', $post->id)->max('id');
 
        // get next joke id
        $next = Post::where('id', '>', $post->id)->min('id');        
        
        return Response::json([
            'previous_joke_id'=> $previous,
            'next_joke_id'=> $next,
            'data' => $this->transform($post)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain,$id)
    {
        if(! $request->title or ! $request->post or !$request->user_id){
            return Response::json([
                'error' => [
                    'message' => 'Insufficient data :('
                ]
            ], 422);
        }

        $post = Post::find($id);
        $post->title = $request->title;
        $post->post = $request->post;
        $post->user_id = $request->user_id;
        $post->save(); 
 
        return Response::json([
                'message' => 'Post Updated Succesfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain,$id)
    {
        $result =  Post::destroy($id);
        return Response::json([
                'data' => $result
        ],200);
    }
    
    
    private function transformCollection($posts){
        $postsArray = $posts->toArray();
        return [
            'total' => $postsArray['total'],
            'per_page' => intval($postsArray['per_page']),
            'current_page' => $postsArray['current_page'],
            'last_page' => $postsArray['last_page'],
            'next_page_url' => $postsArray['next_page_url'],
            'prev_page_url' => $postsArray['prev_page_url'],
            'from' => $postsArray['from'],
            'to' =>$postsArray['to'],
            'data' => array_map([$this, 'transform'], $postsArray['data'])
        ];
    }
     
    private function transform($joke){
        return [
               'post_id' => $joke['id'],
               'title' => $joke['title'],
               'post' => $joke['post'],
               'create_by' => $joke['user']['name']
            ];
    }

    /**
     *
     * Get User Posts
     *
     */
    function  getUserPosts(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $this->posts->userPosts($user);
    }
}
