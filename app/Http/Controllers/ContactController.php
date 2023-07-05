<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Index
    public function index(CompanyRepository $company) {
        $contacts = Contact::where(function ($query) {
            if ($companyId = request()->query("company_id")) {
                $query->where("company_id", $companyId);
            }
        })->where(function ($query) {
            if ($search = request()->query('search')) {
                $query->where("first_name", "LIKE", "%{$search}%");
                $query->orWhere("last_name", "LIKE", "%{$search}%");
                $query->orWhere("email", "LIKE", "%{$search}%");
            }
        })->orderby('id', 'desc')->paginate(10);
        $companies = $company -> company_data();
        return view('contacts.index', ['contacts' => $contacts, 'companies' => $companies]);
    }

    // Create
    public function create() {
        $companies = Company::pluck('name', 'id');
        return view('contacts.create', compact('companies'));
    }

    // Show
    public function show($id) {
        $contact = Contact::findorFail($id);
        abort_if(!isset($contact), 404);
        return view('contacts.show')->with('contact', $contact);
    }

    public function store(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ]);
        
        Contact::create($request->all());
        $message = "Contact has been added successfully!";
        return redirect()->route('contacts.index')->with('message', $message);
    }

    public function edit($id) {
        $companies = Company::pluck('name', 'id');
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update(Request $request, $id) {
        $contact = Contact::findOrFail($id);
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ]);
        $contact->update($request->all());
        $message = "Contact has been updated successfully!";
        return redirect()->route('contacts.index')->with('message', $message);
    }

    public function destroy($id) {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('contacts.index')->with('message', 'Contact has been removed successfully');
    }
}