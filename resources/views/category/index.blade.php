@extends('layout.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Category</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <button class="btn btn-primary float-right" onclick="add()">Add Category</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered dataTable" id="dataTable">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="TableBody">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- modal --}}
        <div class="modal fade" id="CategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="icon">Icon</label>
                            <input type="text" name="icon" id="icon" class="form-control icon-picker">
                        </div>
                        <div class="form-group">
                            <label for="color">Color</label>
                            <input type="color" name="color" id="color" class="form-control" autocomplete="false">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="0">Expense</option>
                                <option value="1">Income</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="buttom" class="btn btn-primary" onclick="upsert()">Save changes</button>
                    </div>

                </div>
            </div>
        </div>
        @include('component.icon-picker')
    @endsection

    @section('scripts')
        <script>
            const table = $('#dataTable').DataTable({
                ajax: {
                    url: "{{ url('/category/get') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'icon',
                        render: function(data, type, row) {
                            return `<div class="badge" style="background-color: ${row.color};">
                                    <i class="${data} text-lg" style="color: white;"></i>
                                </div>`;
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'type',
                        render: function(data, type, row) {
                            return `<span class="badge text-md ${data === 1 ? 'badge-primary' : 'badge-warning'}">
                                    ${data === 1 ? 'INCOME' : 'EXPENSE'}
                                </span>`;
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `<button onclick="edit(${data})" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil"></i>
                                </button>
                                <button onclick="remove(${data})" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>`;
                        }
                    }
                ]
            });

            $('.icon-picker').iconpicker({
                placement: 'bottom',
                animation: false,
                hideOnSelect: true,
                selectedCustomClass: 'btn-success'
            });

            $(document).ready(function() {});

            function add() {
                clear();
                $('#addCategoryModalLabel').text('Add Category');
                $('#CategoryModal').modal('show');
            }

            function edit(id) {
                $.ajax({
                    url: '/category/get/' + id,
                    type: 'get',
                    success: function(response) {
                        $('#id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#description').val(response.data.description);
                        $('#type').val(response.data.type);
                        $('#icon').val(response.data.icon);
                        $('#color').val(response.data.color);

                        $('#addCategoryModalLabel').text('Edit Category');
                        $('#CategoryModal').modal('show');
                    }
                });
            }

            function upsert() {
                const id = $('#id').val();
                const name = $('#name').val();
                const description = $('#description').val();
                const type = $('#type').val();
                const icon = $('#icon').val();
                const color = $('#color').val();

                $.ajax({
                    url: '/category',
                    type: 'POST',
                    data: {
                        id: id,
                        name: name,
                        description: description,
                        type: type,
                        icon: icon,
                        color: color,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: 'Success',
                            text: 'Category has been saved',
                            icon: 'success'
                        });
                        clear();
                        table.ajax.reload();
                        $('#CategoryModal').modal('hide');
                    }
                });
            }

            function remove(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/category/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log(response);
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been removed.',
                                    'success'
                                );
                                table.ajax.reload();
                            }
                        });
                    }
                })
            }

            function clear() {
                $('#id').val('');
                $('#name').val('');
                $('#description').val('');
                $('#type').val('');
                $('#icon').val('');
                $('#color').val('');
            }
        </script>
    @endsection
