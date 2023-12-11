<style>
    /* Style for the dragging effect */
    .dragging {
        background-color: #f8f9fa;
        /* Change as needed */
        border: 2px dashed #6c757d;
        /* Change as needed */
    }
</style>
<section class="vh-100 gradient-custom-2">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-12 col-xl-10">
                <div class="card mask-custom">
                    <div class="card-body p-4 text-white">
                        <div class="text-center pt-3 pb-2">
                            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-todo-list/check1.webp"
                                alt="Check" width="60">
                            <h2 class="my-4">Task List</h2>
                        </div>
                        <div class="container">
                            <div class="row">
                                <!-- Add New Task Button -->
                                <div class="col-md-8">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#projectModalCenter">
                                        Add New Project
                                    </button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                        Add New Task
                                    </button>
                                </div>


                                <!-- Filter by Project Dropdown with Label on Left -->
                                <div class="col-md-4">
                                    <div class="form-row align-items-center">
                                        <div class="col-auto my-1">
                                            <label class="mr-sm-2" for="projectFilter">Filter by Project:</label>
                                        </div>
                                        <div class="col-auto my-1">
                                            <select id="projectFilter" class="form-control">
                                                <option value="">All Projects</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->name }}
                                                    </option>
                                                @endforeach


                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <table class="table text-white mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Task</th>
                                    <th scope="col">Project</th>
                                    <th scope="col">Priority</th>
                                    <th scope="col">Created on</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="taskTable">
                                @if ($tasks->isNotEmpty())

                                    @foreach ($tasks as $task)
                                        <tr class="fw-normal" draggable="true" id="task-{{ $task->id }}"
                                            data-project-id="{{ $task->project->id }}">
                                            <td class="align-middle">
                                                <span>{{ $task->task_name }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span>{{ $task->project->name }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <h6 class="mb-0"><span
                                                        class="badge bg-danger">{{ $task->priority }}</span>
                                                </h6>
                                            </td>
                                            <td class="align-middle">
                                                <span>{{ $task->created_at }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <form action="{{ route('tasks.delete', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-warning"
                                                        onclick="return confirm('Are you sure you want to delete this task?')">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <p>No task for selected projects</p>

                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add new task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Task Name Input -->
                    <div class="input-group mb-3">
                        <input type="text" name="task_name"
                            class="form-control {{ $errors->has('task_name') ? 'is-invalid' : '' }}"
                            placeholder="Task Name" aria-label="Task Name" aria-describedby="basic-addon1">
                        @if ($errors->has('task_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('task_name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <!-- Project Selection Dropdown -->
                    <select class="form-select {{ $errors->has('project_id') ? 'is-invalid' : '' }}" name="project_id"
                        aria-label="Default select example">
                        <option selected>Select Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('project_id'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('project_id') }}</strong>
                        </span>
                    @endif
                    <br>

                    <!-- Task Priority Input -->
                    <div class="input-group mb-3">
                        <input type="number" name="task_priority"
                            class="form-control {{ $errors->has('task_priority') ? 'is-invalid' : '' }}"
                            placeholder="Task Priority (number)" aria-label="Task Priority" min="1">
                        @if ($errors->has('task_priority'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('task_priority') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div class="modal fade" id="projectModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="projectModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="{{ route('projects.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add new Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Project Name Input -->
                    <div class="input-group mb-3">
                        <input type="text" name="project_name"
                            class="form-control {{ $errors->has('project_name') ? 'is-invalid' : '' }}"
                            placeholder="Task Name" aria-label="Task Name" aria-describedby="basic-addon1">
                        @if ($errors->has('project_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('project_name') }}</strong>
                            </span>
                        @endif
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>

    </div>
</div>
