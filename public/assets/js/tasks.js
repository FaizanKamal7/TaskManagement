$(document).ready(function () {
    $('.table').DataTable()
})

const table = document.getElementById('taskTable')

table.addEventListener('dragstart', e => {
    if (e.target.tagName === 'TR') {
        e.target.classList.add('dragging')
    }
})

table.addEventListener('dragover', e => {
    e.preventDefault()
    const activeRow = document
        .elementFromPoint(e.clientX, e.clientY)
        .closest('tr')
    if (activeRow) {
        table.insertBefore(
            document.querySelector('.dragging'),
            activeRow.nextSibling || activeRow
        )
    }
})

table.addEventListener('dragend', e => {
    if (e.target.tagName === 'TR') {
        e.target.classList.remove('dragging')
        updateTaskPriority()
    }
})

function updateTaskPriority () {
    const taskIds = Array.from(table.querySelectorAll('tr'))
        .map(row => {
            // Use a regular expression to extract the UUID from the row id
            const match = row.id.match(/task-(.*)/)
            return match ? match[1] : null
        })
        .filter(id => id !== null) // Filter out any null values
    console.log(taskIds)
    var updateTaskPriorityUrl = "{{ route('tasks.update') }}"

    fetch(updateTaskPriorityUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },
        body: JSON.stringify({
            taskIds: taskIds
        })
    })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error))
}

document
    .getElementById('projectFilter')
    .addEventListener('change', function () {
        var selectedProjectId = this.value
        var rows = document.querySelectorAll('#taskTable tr')

        rows.forEach(row => {
            if (
                selectedProjectId === '' ||
                row.getAttribute('data-project-id') === selectedProjectId
            ) {
                row.style.display = ''
            } else {
                row.style.display = 'none'
            }
        })
    })
