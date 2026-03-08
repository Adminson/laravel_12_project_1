<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelectInputList;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SelectInputListAdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    // GET: /admin/select-input-lists/{data_type}/options
    public function options(string $data_type)
    {
        $items = SelectInputList::query()
            ->where('data_type', $data_type)
            ->orderBy('select_value')
            ->get(['id', 'select_value']);

        return response()->json([
            'data' => $items,
        ]);
    }

    // POST: /admin/select-input-lists
    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_type' => ['required', 'string', 'max:100'],
            'select_value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('select_input_lists', 'select_value')
                    ->where(fn($q) => $q->where('data_type', $request->input('data_type'))),
            ],
        ]);

        SelectInputList::create($validated);

        return $this->options($validated['data_type']);
    }

    // PUT: /admin/select-input-lists/{selectInputList}
    public function update(Request $request, SelectInputList $selectInputList)
    {
        $validated = $request->validate([
            'select_value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('select_input_lists', 'select_value')
                    ->where(fn($q) => $q->where('data_type', $selectInputList->data_type))
                    ->ignore($selectInputList->id),
            ],
        ]);

        $selectInputList->update($validated);

        return $this->options($selectInputList->data_type);
    }

    // DELETE: /admin/select-input-lists/{selectInputList}
    public function destroy(SelectInputList $selectInputList)
    {
        $dataType = $selectInputList->data_type;
        $selectInputList->delete();

        return $this->options($dataType);
    }
}
