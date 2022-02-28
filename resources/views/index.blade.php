<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.4/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">


</head>
<body>
{{-- add new employee modal start --}}
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_employee_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="name">First Name</label>
              <input type="text" name="name" class="form-control" placeholder="First Name" required>
            </div>
          
          </div>
          <div class="my-2">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" placeholder="E-mail" required>
          </div>
         
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="add_employee_btn" class="btn btn-primary">Add Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- add new employee modal end --}}

{{-- edit employee modal start --}}
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_employee_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="emp_id" id="emp_id">
        <input type="hidden" name="emp_avatar" id="emp_avatar">
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="name">First Name</label>
              <input type="text" name="name" id="name" class="form-control" placeholder="First Name" required>
            </div>
            
          </div>
          <div class="my-2">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" required>
          </div>
          
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="edit_employee_btn" class="btn btn-success">Update Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- edit employee modal end --}}

  <div class="container">
    <div class="row my-5">
      <div class="col-lg-12">
        <div class="card shadow">
          <div class="card-header bg-danger d-flex justify-content-between align-items-center">
            <h3 class="text-light">List of Data</h3>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i
                class="bi-plus-circle me-2"></i>Add New Data</button>
          </div>
          <div class="card-body" id="show_all_employees">
            <h1 class="text-center text-secondary my-5">Loading...</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.4/datatables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    //fetch all employees ajax request
    fetchAllEmployees();
    function fetchAllEmployees(){
    $.ajax({
        url: "{{ route('fetchAll') }}",
        method: 'get',
        success:function(res){
           $("#show_all_employees").html(res);
           $("table").DataTable({
               order: [0, 'desc']
           });
        }
    });
}

//delete employee ajax request
$(document).on('click', '.deleteIcon', function(e){
    e.preventDefault();
    let id = $(this).attr('id');
    Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
  if (result.isConfirmed) {
        $.ajax({
        url: "{{ route('delete') }}",
        method: 'post',
        data: {
            id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (res) {
            Swal.fire(
                'Deleted!',
                'Your data has been deleted!',
                'success'
            )
            fetchAllEmployees();
            }
         }); 
        }
    });
});

//update employee ajax request
$("#edit_employee_form").submit(function(e){
    e.preventDefault();
    const fd = new FormData(this);
    $("#edit_employee_btn").text('updating...');
    $.ajax({
        url: "{{ route('update') }}",
        method: 'post',
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        success:function(res){
            if(res.status == 200){
                Swal.fire(
                    'Updated!',
                    'Data updated successfully!',
                    'success'
                )
                fetchAllEmployees();
            }
            $("#edit_employee_btn").text('Update Data');
            $("#edit_employee_form")[0].reset();
            $("#editEmployeeModal").modal('hide');
        }
    });
})

 //edit employee ajax request
 $(document).on('click', '.editIcon', function(e){
            e.preventDefault();
            let id = $(this).attr('id');
           $.ajax({
               url: "{{ route('edit') }}",
               method: 'get',
               data: {
                   id: id,
                   _token:'{{ csrf_token() }}'
               },
               success: function(res){
                   $("#name").val(res.name);
                   $("#email").val(res.email);
                   $("#emp_id").val(res.id);

               }
           });
        });

    //add new employee ajax request
    $("#add_employee_form").submit(function(e){
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_employee_btn").text('Adding...');
        $.ajax({
            url: "{{ route('store') }}",
            method: 'post',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            success:function(res){
                if(res.status == 200){
                    Swal.fire(
                        'Added',
                        'Data Added Successfully!',
                        'success'
                    )
                    fetchAllEmployees();
                }
                $("#add_employee_btn").text('Add Employee');
                $("#add_employee_form")[0].reset();
                $("#add_employee_modal").modal('hide');

            }
        });
       
    });
</script>

</body>
</html>