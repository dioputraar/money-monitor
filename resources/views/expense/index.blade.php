@extends('layout.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Expense</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <button class="btn btn-primary float-right" onclick="add()">Add Expense</button>
            </div>
            <div class="card-body">
                <table class="table table-bordered dataTable" id="dataTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="TableBody">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- modal --}}
        <div class="modal fade" id="ExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="total">Total</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">IDR</span>
                                </div>
                                <input type="text" name="total_input" id="total_input" class="form-control" 
                                    oninput="formatCurrency(this)" 
                                    data-raw-value="">
                                <input type="hidden" name="total" id="total" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" id="category" class="form-control">
                            </select>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="buttom" class="btn btn-primary" onclick="upsert()">Save changes</button>
                    </div>

                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            const table = $('#dataTable').DataTable({
                ajax: {
                    url: "{{ url('/expense/get') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'date',
                        render: function(data, type, row) {
                            const date = new Date(data);
                            const formattedDate = `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}`;
                            return `${formattedDate}`;
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'category',
                        render: function(data, type, row) {
                            return `<div class="badge" style="background-color: ${data.color};">
                                    <i class="${data.icon} text-lg" style="color: white;"></i>
                                    
                                </div> ${data.name}`;
                        }
                    },
                    {
                        data: 'total',
                        render: function(data, type, row) {
                            const formattedTotal = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            return `<span class="float-right">${formattedTotal}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `<button onclick="edit(${data})" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pencil"></i>
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

            $(document).ready(function() {
                setCategory();
            });

            function add() {
                clear();
                $('#addExpenseModalLabel').text('Add Expense');
                $('#ExpenseModal').modal('show');
            }

            function edit(id) {
                $.ajax({
                    url: '/expense/get/' + id,
                    type: 'get',
                    success: function(response) {
                        $('#id').val(response.data.id);
                        $('#name').val(response.data.name);
                        $('#description').val(response.data.description);
                        const date = new Date(response.data.date);
                        const formattedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                        $('#date').val(formattedDate);
                        $('#total').val(response.data.total);
                        $('#category').val(response.data.category.id);
                        $('#total_input').val(response.data.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

                        $('#addExpenseModalLabel').text('Edit Expense');
                        $('#ExpenseModal').modal('show');
                    }
                });
            }

            function upsert() {
                const id = $('#id').val();
                const date = $('#date').val();
                const name = $('#name').val();
                const description = $('#description').val();
                const total = $('#total').val();
                const category = $('#category').val();
                

                $.ajax({
                    url: '/expense',
                    type: 'POST',
                    data: {
                        id: id,
                        date: date,
                        name: name,
                        description: description,
                        total: total,
                        category: category,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: 'Success',
                            text: 'Expense has been saved',
                            icon: 'success'
                        });
                        clear();
                        table.ajax.reload();
                        $('#ExpenseModal').modal('hide');
                    }
                });
            }

            function clear() {
                $('#id').val('');
                $('#date').val('{{ date('Y-m-d') }}');
                $('#name').val('');
                $('#description').val('');
                $('#category').val('');
                $('#total').val('');
                $('#total_input').val('');
            }

            function setCategory() {
                $.ajax({
                    url: '/category/get',
                    type: 'get',
                    success: function(response) {
                        let options = '<option value="">Select Category</option>';
                        response.data.filter(category => category.type === 0).forEach(function(category) {
                            options += `<option value="${category.id}">
                                <i class="${category.icon}" style="background-color:${category.color}"></i>
                                ${category.name}
                            </option>`;
                        });
                        $('#category').html(options);
                    }
                });
            }

            function formatCurrency(el) {
                let input = el.value.replace(/[^0-9]/g, ''); // Ambil hanya angka
                if (input === '') {
                document.getElementById('total').value = '';
                el.value = '';
                return;
                }

                // Simpan angka asli ke input hidden
                document.getElementById('total').value = input;

                // Format ribuan (misalnya: 1000000 -> 1.000.000)
                el.value = input.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        </script>
    @endsection
