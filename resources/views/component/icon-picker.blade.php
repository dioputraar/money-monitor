<div class="modal fade" id="iconPickerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">Choose Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <button id="iconPickerButton" class="btn btn-outline-primary">
                    <i class="fa fa-icons"></i> Choose Icon
                </button>
                <input type="hidden" id="icon" name="icon">
                <div class="mt-3">
                    <p>Selected icon : <span id="selectedIconPreview" class="fs-3"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#iconPickerButton').iconpicker({
                placement: 'bottom',
                animation: false,
                hideOnSelect: true,
                selectedCustomClass: 'btn-success'
            });

            $('#iconPickerButton').on('iconpickerSelected', function(event) {
                $('#icon').val(event.iconpickerValue);
                $('#selectedIconPreview').html('<i class="fa ' + event.iconpickerValue + '"></i>');
                var modal = bootstrap.Modal.getInstance(document.getElementById('iconPickerModal'));
                modal.hide();
            });
        });
    </script>
@endpush
