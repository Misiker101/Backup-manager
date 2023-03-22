<div class="container mb-5">
    <div class="d-flex align-items-end pt-4">
        <h5 class="mb-0">
            Laravel Backup Panel
        </h5>

        <button id="create-backup" class="btn btn-primary btn-sm ml-auto px-3">
            Create Backup
        </button>
        <div class="dropdown ml-3">
            <button class="btn btn-primary btn-sm dropdown-toggle px-3" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.7875rem" height="0.7875rem" viewBox="0 0 24 24"
                     fill="currentColor">
                    <path class="heroicon-ui" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
                </svg>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" id="create-backup-only-db" wire:click.prevent="">
                    Create database backup
                </a>
                <a class="dropdown-item" href="#" id="create-backup-only-files" wire:click.prevent="">
                    Create files backup
                </a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                
            </div>

            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-end">
                    @if(count($disks))
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach($disks as $disk)
                                <label class="btn btn-outline-secondary {{ $activeDisk === $disk ? 'active' : '' }}"
                                       wire:click="getFiles('{{ $disk }}')"
                                >
                                    <input type="radio" name="options" {{ $activeDisk === $disk ? 'checked' : '' }}>
                                    {{ $disk }}
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <button class="btn btn-primary btn-sm btn-refresh ml-auto"
                            wire:loading.class="loading"
                            wire:loading.attr="disabled"
                            wire:click="getFiles"
                            {{ $activeDisk ? '' : 'disabled' }}
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="0.7875rem" height="0.7875rem" viewBox="0 0 24 24" fill="currentColor">
                            <path class="heroicon-ui" d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7zM18 5.3V3a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.9A7 7 0 0 0 5 12a1 1 0 1 1-2 0 9 9 0 0 1 15-6.7z"/>
                        </svg>
                    </button>
                </div>

                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th scope="col">Path</th>
                        <th scope="col">Created at</th>
                        <th scope="col">Size</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($files as $file)
                        <tr>
                            <td>{{ $file['path'] }}</td>
                            <td>{{ $file['date'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td class="text-right pr-3">
                                <a class="action-button mr-2" href="#" target="_blank" wire:click.prevent="downloadFile('{{ $file['path'] }}')">
                                    Download
                                </a>
                                <a class="action-button del" href="#" target="_blank" wire:click.prevent="showDeleteModal({{ $loop->index }})">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if(!count($files))
                        <tr>
                            <td class="text-center" colspan="4">
                                {{ 'No backups present' }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h5 class="modal-title mb-3">Delete backup</h5>
                                @if($deletingFile)
                                <span class="text-muted">
                                    Are you sure you want to delete the backup created at {{ $deletingFile['date'] }} ?
                                </span>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary cancel-button" data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-danger delete-button" wire:click="deleteFile">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            @this.updateBackupStatuses()

            @this.on('backupStatusesUpdated', function () {
                @this.getFiles()
            })

            @this.on('showErrorToast', function (message) {
                Toastify({
                    text: message,
                    duration: 10000,
                    gravity: 'bottom',
                    position: 'right',
                    backgroundColor: 'red',
                    className: 'toastify-custom',
                }).showToast()
            })

            const backupFun = function (option = '') {
                Toastify({
                    text: 'Creating a new backup in the Google Drive...' + (option ? ' (' + option + ')' : ''),
                    duration: 5000,
                    gravity: 'bottom',
                    position: 'center',
                    backgroundColor: '#1c816e',
                    className: 'toastify-custom',
                }).showToast()

                @this.createBackup(option)
            }

            $('#create-backup').on('click', function () {
                backupFun()
            })
            $('#create-backup-only-db').on('click', function () {
                backupFun('only-db')
            })
            $('#create-backup-only-files').on('click', function () {
                backupFun('only-files')
            })

            const deleteModal = $('#deleteModal')
            @this.on('showDeleteModal', function () {
                deleteModal.modal('show')
            })
            @this.on('hideDeleteModal', function () {
                deleteModal.modal('hide')
            })

            deleteModal.on('hidden.bs.modal', function () {
                @this.deletingFile = null
            })
        })
    </script>
</div>
