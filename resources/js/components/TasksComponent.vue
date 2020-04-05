<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">List of tasks (total <b>{{ countTasks }}</b>)</div>

                    <div class="card-body">
                        <table class="table">
                            <tbody>
                            <tr v-for="task in taskList">
                                <td>
                                    <label>
                                        <input type="checkbox"
                                               v-on:click="toggleTask(task)"
                                               v-if="task.status !== 'disabled'"
                                               :checked="task.status === 'finished'"
                                        >
                                    </label>
                                </td>
                                <td v-on:click="showEditTaskForm(task.id)">
                                    <s v-if="task.status === 'finished'">{{ task.title }}</s>
                                    <span v-if="task.status !== 'finished' && task.status !== 'disabled'">{{ task.title }}</span>
                                    <span class="text-muted" v-if="task.status === 'disabled'">{{ task.title }}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button v-on:click="showEditTaskForm()" v-if="showForm === false">Create Task</button>
                        <button v-on:click="showForm = false" v-if="showForm">&times; Close</button>
                    </div>
                    <div class="card-body" v-if="showForm">
                        <div class="row">
                            <div class="col-12">
                                <label for="title" class="label">Title</label>
                                <input type="text" class="form-control" id="title" v-model="title">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="desc" class="label">Description</label>
                                <input type="text" class="form-control" id="desc" v-model="description">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button v-on:click="createTask" v-if="showForm && !taskId">Create</button>
                                <button v-on:click="updateTask(taskId, {title: title, description: description})"
                                        v-if="showForm && taskId">Update</button>
                                <button v-on:click="deleteTask(taskId)" v-if="showForm && taskId">Delete</button>
                                <button v-on:click="disableTask(taskId)" v-if="showForm && taskId">Disable</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TaskProvider from '../providers/tasks.provider'

    export default {
        mounted() {
            this.getTaskList()
        },
        data () {
            return {
                countTasks: 'loading...',
                showForm: false,
                title: '',
                description: '',
                taskId: 0,
                taskList: [],
            }
        },
        methods: {
            getTaskList () {
                TaskProvider.get('').then(res => {
                    this.countTasks = res.data.length;
                    this.taskList = res.data;
                    console.log(this.taskList)

                }).catch(err => console.log(err));
            },
            createTask() {
                TaskProvider.post({
                    id: this.taskId,
                    title: this.title,
                    description: this.description,
                }).then(resp => {
                    this.taskList.push(resp);
                });
            },
            updateTask(id, data) {
                TaskProvider.update(id, data).then(resp => {
                    for(const task of resp.data) {
                        for(const storedTaskKey in this.taskList) {
                            if (this.taskList.hasOwnProperty(storedTaskKey)
                                && this.taskList[storedTaskKey]['id'] === task.id) {

                                this.taskList[storedTaskKey].title = task.title;
                                this.taskList[storedTaskKey].description = task.description;
                                this.taskList[storedTaskKey].status = task.status;

                            }
                        }
                    }
                });
            },
            deleteTask(id) {
                TaskProvider.delete(id).then(() => {
                    for(const storedTaskKey in this.taskList) {
                        if (this.taskList.hasOwnProperty(storedTaskKey)
                            && this.taskList[storedTaskKey]['id'] === id) {

                            this.taskList.splice(storedTaskKey, 1);
                            this.showForm = false;
                        }
                    }
                })
            },
            showEditTaskForm(id) {
                if (id) {
                    TaskProvider.get(id).then(response => {
                        this.taskId = response.data.id;
                        this.title = response.data.title;
                        this.description = response.data.description;
                        this.showForm = true;
                    });
                } else {
                    this.taskId = 0;
                    this.title = '';
                    this.description = '';
                    this.showForm = true
                }
            },
            toggleTask(task) {
                this.updateTask(task.id, {
                    status: task.status === 'finished' ? 'active' : 'finished',
                })
            },
            disableTask(taskId) {
                this.updateTask(taskId, {
                    status: 'disabled',
                })
            }
        }
    }
</script>
