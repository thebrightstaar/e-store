<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FAQController extends BaseController
{
    public function index(){
        try {
            $FAQ = FAQ::all();
            if ($FAQ->count() == 0) {
                return $this->SendError('No Questions to recive');
            }
            return $this->SendResponse($FAQ, 'Questions recived successfully');
        } catch (\Throwable $th) {
            return $this->SendError('something went wrong', $th->getMessage());
        }
    }

    public function store(Requets $request){
        try {
            $validate = Validator::make($request->all(), [
                'question_ar' => 'required|unique:f_a_q_s',
                'question_en' => 'required|unique:f_a_q_s',
                'answer_ar' => 'required',
                'answer_en' => 'required',
            ]);
            if ($validate->fails()) {
                return $this->SendError('validate error', $validate->errors());
            }
            $user = Auth::user();
            if ($user->is_admin == 1) {
                $FAQ = FAQ::create($request->all());
                return $this->SendResponse($FAQ, 'Question added successfully');
            }else{
                return $this->SendError('you do not have rights');
            }
        } catch (\Throwable $th) {
            return $this->SendError('something went wrong', $th->getMessage());
        }
    }

    public function update(Request $request, $id){
        try {
            $validate = Validator::make($request->all(), [
                'question_ar' => 'required|unique:f_a_q_s',
                'question_en' => 'required|unique:f_a_q_s',
                'answer_ar' => 'required',
                'answer_en' => 'required',
            ]);
            if ($validate->fails()) {
                return $this->SendError('validate error', $validate->errors());
            }
            $user = Auth::user();
            if ($user->is_admin == 1) {
                $FAQ = FAQ::find($id);
                if (is_null($FAQ)) {
                    return $this->SendError('Question is not found');
                }
                $FAQ->question_ar = $request->question_ar;
                $FAQ->question_en = $request->question_en;
                $FAQ->answer_ar = $request->answer_ar;
                $FAQ->answer_en = $request->answer_en;
                $FAQ->save();
                return $this->SendResponse($FAQ, 'Question updated successfully');
            }else{
                return $this->SendError('you do not have rights');
            }
        } catch (\Throwable $th) {
            return $this->SendError('something went wrong', $th->getMessage());
        }
    }

    public function delete($id){
        try {
            $user = Auth::user();
            if ($user->is_admin == 1) {
                $FAQ = FAQ::find($id);
                if (is_null($FAQ)) {
                    return $this->SendError('Question is not found');
                }
                $FAQ->delete();
                return $this->SendResponse($FAQ, 'Question deleted successfully');
            }else{
                return $this->SendError('you do not have right');
            }
        } catch (\Throwable $th) {
            return $this->SendError('Something went wrong', $th->getMessage());
        }
    }
}
