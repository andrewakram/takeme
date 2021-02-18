<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\WorkerRepositoryInterface;
use App\Models\Category;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class WorkerController extends Controller
{
    protected $workerRepository;
    public function __construct(WorkerRepositoryInterface $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }

    public function index()
    {
        $all = $this->workerRepository->workerCompany();
        $workers = $all['workers'];
        $categories = $all['categories'];
        $cities = $all['cities'];
        return view('admin.worker.index',compact('workers','categories','cities'));
    }

    public function workers($type)
    {
        $workers = $this->workerRepository->workerApp($type);
        return view('admin.worker.index',compact('workers','type'));
    }

    public function getSubCat(Request $request)
    {
        $categories = $this->workerRepository->getSubCat($request);
        return response()->json($categories);

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required|min:6',
            'cat_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'city_id' => 'required|exists:cities,id',
            'id_image' => 'required',
            'image' => 'required',
            'description' => 'required',
        ]);
        $worker = $this->workerRepository->storeWorkerCompany($request);

        if($worker == 'email_exist')
            return redirect(route('workers_company.index'))->with('error','Email already exists');
        elseif($worker == 'phone_exist')
            return redirect(route('workers_company.index'))->with('error','Phone already exists');
        else
            return redirect(route('workers_company.index'))->with('success','Worker added successfully');
    }

    public function show($id)
    {
        $worker = Worker::whereId($id)->first();
        return view('admin.worker.show',compact('worker'));
    }

    public function changeStatus(Request $request)
    {
        $this->validate($request,[
            'worker_id' => 'required|exists:workers,id'
        ]);

        $user = $this->workerRepository->changStatus($request->worker_id);
        if($user == 'suspend')
            return back()->with('success','User suspended successfully');
        else
            return back()->with('success','User activated successfully');
    }

    public function activeContract(Request $request)
    {
        $this->validate($request,[
            'worker_id' => 'required|exists:workers,id'
        ]);

        $this->workerRepository->activeContract($request->worker_id);
        return back()->with('success','User activated successfully');
    }

    public function edit($id)
    {
        $worker = Worker::whereId($id)->first();
        $workers=DB::table("workers")->where("id",$id)->first();
        if($workers->cat_id == ""){
            $cats = Category::where('parent_id',Null)->where('type',1)->get();
        }else{
            $selectedCats=explode(",",$workers->cat_id);
            $parentCat=Category::where('id',$selectedCats[0])->first();
            $cats = Category::where('id',$parentCat->parent_id)->where('type',1)->get();
        }


        return view('admin.worker.single',compact('worker','cats'));
    }

    public function update(Request $request)
    {
        /*return $request->cat_id;*/
        $this->validate($request,
            [
                'worker_id' => 'required|exists:workers,id',
                'cat_id' => 'sometimes',
                'name' => 'required',
                'email' => 'required|unique:workers,email,'.$request->worker_id,
                'phone' => 'required|unique:workers,phone,'.$request->worker_id,
                'image' => 'sometimes|image',
                'id_image' => 'sometimes|image'
            ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'phone.required' => 'Phone is required',
                'image.image' => 'Image is invalid',
                'id_image.image' => 'Image is invalid',
            ]
        );


        $email_check = Worker::where('id','!=',$request->worker_id)->where('email', $request->email)->first();
        $phone_check = Worker::where('id','!=',$request->worker_id)->where('phone', $request->phone)->first();

        if($email_check) return back()->with('error', 'Sorry,email already exists,please change to another one');
        if($phone_check) return back()->with('error', 'Sorry,phone already exists,please change to another one');

        $this->workerRepository->updateWorker($request);
        return back()->with('success', 'Updated Successfully');
        //return redirect('/workers/'.$request->worker_id)->with('success', 'Updated Successfully');

    }

    /*public function edit($id)
    {
        $worker = Worker::whereId($id)->first();
        $cats = Category::where('parent_id',Null)->where('type',1)->get();
        return view('admin.worker.single',compact('worker','cats'));
    }*/



    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $workers = $this->workerRepository->search($request);
        $type='';
        return view('admin.worker.index',compact('workers','type'));
    }

    public function showAdminContractPdf()
    {
        $worker_pdfs = $this->workerRepository->showAdminContractPdf();
        return view('admin.worker.pdf.show',compact('worker_pdfs'));
    }

    public function uploadAdminContractPdf(Request $request)
    {
        $this->workerRepository->uploadAdminContractPdf($request);
        return back()->with('success','Uploaded Successfully');
    }

    public function editAdminContractPdf(Request $request)
    {
        $this->workerRepository->editAdminContractPdf($request);
        return back()->with('success','Edit Successfully');
    }
}
