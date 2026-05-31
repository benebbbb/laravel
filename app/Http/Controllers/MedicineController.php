<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::where('created_by', Auth::id());

        if ($request->filled('search')) {
            $query->where('medicine_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('expiration_date')) {
            $query->whereDate('expiration_date', $request->expiration_date);
        }

        $medicines = $query->orderBy('expiration_date')->paginate(10)->withQueryString();

        return view('medicines.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_name'   => 'required|string|max:255',
            'description'     => 'nullable|string',
            'category'        => 'required|string|max:100',
            'quantity'        => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        Medicine::create([
            ...$request->only('medicine_name', 'description', 'category', 'quantity', 'expiration_date'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('medicines.index')
            ->with('toast_success', 'Medicine added successfully.');
    }

    public function update(Request $request, Medicine $medicine)
    {
        abort_if($medicine->created_by !== Auth::id(), 403);

        $request->validate([
            'medicine_name'   => 'required|string|max:255',
            'description'     => 'nullable|string',
            'category'        => 'required|string|max:100',
            'quantity'        => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $medicine->update($request->only('medicine_name', 'description', 'category', 'quantity', 'expiration_date'));

        return redirect()->route('medicines.index')
            ->with('toast_success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        abort_if($medicine->created_by !== Auth::id(), 403);

        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('toast_success', 'Medicine deleted successfully.');
    }
}
