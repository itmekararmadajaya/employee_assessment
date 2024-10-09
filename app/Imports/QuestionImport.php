<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\QuestionLevel;
use App\Models\QuestionOption;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $question_level = QuestionLevel::where('name', $row['level'])->first();
        $question = new Question;
        $question->question_level_id = $question_level->id;
        $question->aspect = $row['aspect'];
        $question->question = $row['question'];
        $question->weight = $row['weight'];

        if($question->save()){
            for($i=1; $i<=5; $i++){
                $option = new QuestionOption;
                $option->question_id = $question->id;
                $option->option = $i;
                $option->content = $row[$i];
                $option->save();
            }
        }
    }

    public function rules(): array
    {
        return [
            'level' => 'required|exists:question_levels,name',
            'aspect' => 'required|string|max:255',
            'question' => 'required|string|max:255',
            'weight' => 'required|integer',
            '1' => 'required|string|max:255',
            '2' => 'required|string|max:255',
            '3' => 'required|string|max:255',
            '4' => 'required|string|max:255',
            '5' => 'required|string|max:255',
        ];
    }
}
