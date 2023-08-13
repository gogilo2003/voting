<?php

namespace App\Imports;

use App\Models\Member;
use App\Support\SMSSender;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MembersImport implements ToModel, WithHeadingRow
{
    use SMSSender;

    public function model(array $row)
    {
        // Pre-format the phone number to E.164 format
        try {
            $phone = new PhoneNumber($row['phone'], 'KE');
            $row['phone'] = $phone->formatE164();
        } catch (\Throwable $th) {
            // throw $th;
        }

        // Validate the data to ensure it's correct before creating a member
        $validator = Validator::make($row, [
            'name' => 'required|string',
            'phone' => [
                'required',
                Rule::unique('users', 'phone'),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email'),
            ],
        ]);

        if ($validator->fails()) {
            // Handle validation errors if needed (e.g., log them, store in a separate array, etc.)
            Log::info($validator->errors()->all());
            return null;
        }

        // Create a new member with the validated data
        $member = new Member();
        $member->name = $row['name'];
        $member->phone = $row['phone'];
        $member->email = $row['email'];

        $password = $this->genPassword();
        $member->password = bcrypt($password);

        // $member->save();
        $this->sendWelcomeSMS($member->phone, $password);

        return $member;
    }
}
