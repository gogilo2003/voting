<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Member;
use App\Support\SMSSender;
use App\Imports\MembersImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Http\Requests\PhotoUploadRequest;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Requests\UploadMembersRequest;

class MemberController extends Controller
{
    use SMSSender;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $members = null;
        if ($search) {
            $strSearch = '%' . $search . '%';

            $members = Member::where('name', 'LIKE', $strSearch)->paginate(10);
        } else {
            $members = Member::paginate(10);
        }
        return Inertia::render('Members/Index', ['members' => $members, "retSearch" => $search]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request)
    {
        $member = new Member();

        $member->name = $request->name;
        $member->phone = $request->phone;
        $member->email = $request->email;
        $password = $this->genPassword();
        $member->password = bcrypt($password);

        $member->save();

        $this->sendWelcomeSMS($member->phone, $password);

        return redirect()->back()->with('success', 'Member created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->name = $request->name;
        $member->phone = $request->phone;
        $member->email = $request->email;
        // $password = $this->genPassword();
        // $member->password = bcrypt($password);

        $member->save();

        // $this->sendWelcomeSMS($member->phone, $password);

        return redirect()->back()->with('success', 'Member updated successfully');
    }

    /**
     * Mass upload of members
     */
    public function upload(UploadMembersRequest $request)
    {
        $file = $request->file('file');

        $import = new MembersImport;

        Excel::import($import, $file);

        return redirect()->back()->with('success', 'Members uploaded successfully!');
    }

    /**
     * Download user list
     */
    function download($filter = 'all')
    {
        $members = Member::orderBy('name')->where('is_admin', 0)->get(); // Replace 'Member' with your model and query as needed

        $pdf = PDF::loadView('pdf.members', compact('members'));
        $pdf->setOption(['defaultFont' => 'serif']);

        return $pdf->download('members.pdf');
    }

    /**
     * Reset user password.
     */
    public function password(Member $member)
    {
        $password = $this->genPassword();
        $member->password = bcrypt($password);
        $member->save();

        $this->sendWelcomeSMS($member->phone, $password);
        return redirect()->back()->with('success', 'Member password reset successfully');
    }

    /**
     * Reset user passwords.
     */
    public function passwords()
    {
        Member::where('is_admin', 0)->get()->each(function ($member) {
            $password = $this->genPassword();
            $member->password = bcrypt($password);
            $member->save();

            $this->sendWelcomeSMS($member->phone, $password);
        });

        return redirect()->back()->with('success', 'Members passwords reset successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->back()->with('success', 'Member deleted successfully');
    }

    /**
     * Upload members Photo
     */
    function photo(PhotoUploadRequest $request)
    {
        $member = User::find($request->id);
        $member->updateProfilePhoto($request->photo);
        return redirect()->back()->with('success', 'Photo uploaded');
    }
}
