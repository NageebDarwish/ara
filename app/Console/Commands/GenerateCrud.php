<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class GenerateCrud extends Command
{
    protected $signature = 'class {name} {folder}';

    protected $description = 'Generate migration, model, request, controller, and repository for CRUD operations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $folder = $this->argument('folder');
        $modelName = Str::studly($name);
        $requestName = $modelName.'Request';
        $controllerName = $modelName.'Controller';
        $repositoryName = $modelName.'Repository';
        $migrationName = 'create_'.Str::snake($name).'_table';

        Artisan::call('make:model', [
            'name' => "{$modelName}",
            '-m' => true,
        ]);
        $this->info('Model and migration created.');

        // Generate request
        Artisan::call('make:request', [
            'name' => "{$folder}/{$requestName}",
        ]);
        $this->info('Request created.');

        // Generate controller
        Artisan::call('make:controller', [
            'name' => "{$folder}/{$controllerName}",
        ]);
        $this->info('Controller created.');

        // Generate repository
        $this->generateRepositoryStructure($folder, $repositoryName, $modelName);
        $this->info('Repository created.');

        // Add controller structure
        $controllerPath = app_path("Http/Controllers/{$folder}/{$controllerName}.php");
        $this->generateControllerStructure($controllerPath, $requestName, $repositoryName, $controllerName);
    }

    protected function generateRepositoryStructure($folder, $repositoryName, $modelName)
    {
        $path = app_path("Repositories/{$folder}/{$repositoryName}.php");

        $content = "<?php\n\n";
        $content .= "namespace App\Repositories\\{$folder};\n\n";
        $content .= "use App\Models\\{$modelName};\n";
        $content .= "use Illuminate\Support\Facades\Storage;\n";
        $content .= "use App\Helpers\UploadFiles;\n\n";
        $content .= "class {$repositoryName}\n";
        $content .= "{\n";
        $content .= "    private \$model;\n\n";
        $content .= "    public function __construct({$modelName} \$model)\n";
        $content .= "    {\n";
        $content .= "        \$this->model = \$model;\n";
        $content .= "    }\n\n";
        $content .= "    public function all()\n";
        $content .= "    {\n";
        $content .= "        return \$this->model->all();\n";
        $content .= "    }\n\n";
        $content .= "    public function find(\$id)\n";
        $content .= "    {\n";
        $content .= "        return \$this->model->findOrFail(\$id);\n";
        $content .= "    }\n\n";
        $content .= "    public function create(array \$data)\n";
        $content .= "    {\n";
        $content .= "        if (isset(\$data['image'])) {\n";
        $content .= "            \$imagePath = UploadFiles::upload(\$data['image'], '".Str::snake($modelName)."');\n";
        $content .= "            \$data['image'] = \$imagePath;\n";
        $content .= "        }\n\n";
        $content .= "        return \$this->model->create(\$data);\n";
        $content .= "    }\n\n";
        $content .= "    public function update(\$id, array \$data)\n";
        $content .= "    {\n";
        $content .= "        \$model = \$this->model->findOrFail(\$id);\n";
        $content .= "        if (isset(\$data['image'])) {\n";
        $content .= "            if (\$model->image) {\n";
        $content .= "                Storage::delete('public/".Str::snake($modelName)."/'.basename(\$model->image));\n";
        $content .= "            }\n";
        $content .= "            \$data['image'] = UploadFiles::upload(\$data['image'], '".Str::snake($modelName)."');\n";
        $content .= "        }\n";
        $content .= "        \$model->update(\$data);\n\n";
        $content .= "        return \$model;\n";
        $content .= "    }\n\n";
        $content .= "    public function delete(\$id)\n";
        $content .= "    {\n";
        $content .= "        \$model = \$this->model->findOrFail(\$id);\n";
        $content .= "        if (\$model->image) {\n";
        $content .= "            Storage::delete('public/".Str::snake($modelName)."/'.basename(\$model->image));\n";
        $content .= "        }\n\n";
        $content .= "        return \$model->delete();\n";
        $content .= "    }\n";
        $content .= "}\n";

        file_put_contents($path, $content);
    }

    protected function generateControllerStructure($path, $requestName, $repositoryName, $controllerName)
    {
        $folder = $this->argument('folder');
        $folderName = Str::lower($this->argument('folder'));
        $name = Str::lower($this->argument('name'));
        $content = "<?php\n\n";
        $content .= "namespace App\Http\Controllers\\{$folder};\n\n";
        $content .= "use App\Http\Controllers\Controller;\n";
        $content .= "use App\Http\Requests\\{$folder}\{$requestName};\n";
        $content .= "use App\Repositories\\{$folder}\{$repositoryName};\n";
        $content .= "use Illuminate\Http\Request;\n\n";
        $content .= "class {$controllerName} extends Controller\n";
        $content .= "{\n";
        $content .= "    protected \$repository;\n\n";
        $content .= "    public function __construct({$repositoryName} \$repository)\n";
        $content .= "    {\n";
        $content .= "        \$this->repository = \$repository;\n";
        $content .= "    }\n\n";
        $content .= "    public function index()\n";
        $content .= "    {\n";
        $content .= "        \$data = \$this->repository->all();\n\n";
        $content .= "        return view('{$folderName}.modules.{$name}.index', compact('data'));\n";
        $content .= "    }\n\n";
        $content .= "    public function create()\n";
        $content .= "    {\n";
        $content .= "        return view('{$folderName}.modules.{$name}.create');\n";
        $content .= "    }\n\n";
        $content .= "    public function store({$requestName} \$request)\n";
        $content .= "    {\n";
        $content .= "        \$data = \$request->validated();\n";
        $content .= "        \$this->repository->create(\$data);\n\n";
        $content .= "        return redirect()->route('{$folderName}.{$name}.index')->with('success', 'Created successfully.');\n";
        $content .= "    }\n\n";
        $content .= "    public function edit(\$id)\n";
        $content .= "    {\n";
        $content .= "        \$data = \$this->repository->find(\$id);\n\n";
        $content .= "        return view('{$folderName}.modules.{$name}.edit', compact('data'));\n";
        $content .= "    }\n\n";
        $content .= "    public function update(Request \$request, \$id)\n";
        $content .= "    {\n";
        $content .= "        \$data = \$request->all();\n";
        $content .= "        \$this->repository->update(\$id, \$data);\n\n";
        $content .= "        return redirect()->route('{$folderName}.{$name}.index')->with('success', 'Updated successfully.');\n";
        $content .= "    }\n\n";
        $content .= "    public function destroy(\$id)\n";
        $content .= "    {\n";
        $content .= "        \$this->repository->delete(\$id);\n\n";
        $content .= "        return redirect()->route('{$folderName}.{$name}.index')->with('success', 'Deleted successfully.');\n";
        $content .= "    }\n";
        $content .= "}\n";

        file_put_contents($path, $content);
    }
}