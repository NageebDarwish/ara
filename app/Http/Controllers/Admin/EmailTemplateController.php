<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\EmailTemplateRepository;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplateController extends Controller
{
    protected $repository;

    public function __construct(EmailTemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.modules.email-templates.index');
    }

    public function getEmailTemplatesData(Request $request)
    {
        $templates = $this->repository->getForDataTable();

        return DataTables::of($templates)
            ->addIndexColumn()
            ->addColumn('status', function ($template) {
                if ($template->is_active) {
                    return '<span class="badge badge-success">Active</span>';
                }
                return '<span class="badge badge-secondary">Inactive</span>';
            })
            ->addColumn('trigger', function ($template) {
                return $template->trigger_event ? '<span class="badge badge-info">' . ucwords(str_replace('_', ' ', $template->trigger_event)) . '</span>' : 'N/A';
            })
            ->addColumn('sent_count', function ($template) {
                $count = EmailLog::where('email_template_id', $template->id)
                    ->where('status', 'sent')
                    ->count();
                return '<span class="badge badge-primary">' . $count . ' sent</span>';
            })
            ->addColumn('actions', function ($template) {
                $actions = '<a href="' . route('admin.email-templates.edit', $template->id) . '" class="btn btn-warning btn-sm" title="Edit Template"><i class="fa fa-edit"></i></a>';
                $actions .= ' <a href="' . route('admin.email-templates.preview', $template->id) . '" class="btn btn-info btn-sm" target="_blank" title="Preview"><i class="fa fa-eye"></i></a>';
                $actions .= ' <button class="btn btn-sm btn-' . ($template->is_active ? 'secondary' : 'success') . ' toggle-status-btn" data-id="' . $template->id . '" title="' . ($template->is_active ? 'Deactivate' : 'Activate') . '"><i class="fa fa-power-off"></i></button>';
                return $actions;
            })
            ->rawColumns(['status', 'trigger', 'sent_count', 'actions'])
            ->make(true);
    }

    public function edit($id)
    {
        $template = $this->repository->find($id);
        $sentCount = EmailLog::where('email_template_id', $id)->where('status', 'sent')->count();
        $failedCount = EmailLog::where('email_template_id', $id)->where('status', 'failed')->count();

        return view('admin.modules.email-templates.edit', compact('template', 'sentCount', 'failedCount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'html_content' => 'required',
        ]);

        $data = $request->only(['subject', 'html_content', 'description', 'is_active']);
        $data['is_active'] = $request->has('is_active') ? true : false;

        $this->repository->update($id, $data);

        return redirect()->route('admin.email-templates.index')->with('success', 'Email template updated successfully.');
    }

    public function preview($id)
    {
        $template = $this->repository->find($id);

        // Preview with sample data
        $sampleData = [
            'user_name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $html = $template->render($sampleData);

        return response($html)->header('Content-Type', 'text/html');
    }

    public function toggleStatus(Request $request)
    {
        $template = $this->repository->find($request->id);
        $template->update(['is_active' => !$template->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $template->is_active,
            'message' => 'Status updated successfully.'
        ]);
    }

    public function logs($id)
    {
        $template = $this->repository->find($id);
        $logs = EmailLog::where('email_template_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.modules.email-templates.logs', compact('template', 'logs'));
    }
}
