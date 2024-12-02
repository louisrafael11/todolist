<template>
  <div class="max-w-lg mx-auto p-8 bg-gradient-to-r from-teal-100 to-pink-200 rounded-2xl shadow-lg">
    <h2 class="text-center text-4xl font-poppins font-semibold text-gray-800 mb-8">
      To-do List
    </h2>

    <!-- Form to Add a Task -->
    <form @submit.prevent="addTask" class="flex gap-4 mb-6">
      <input
        v-model="newTask"
        type="text"
        placeholder="Add a new task"
        required
        class="flex-grow px-5 py-3 border-2 border-teal-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-lg placeholder-teal-300 transition-all duration-300"
      />
      <button
        type="submit"
        class="px-6 py-3 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transform transition-all duration-300"
      >
        Add Task
      </button>
    </form>

    <!-- Display Tasks -->
    <ul class="space-y-4">
      <li
        v-for="task in tasks"
        :key="task.id"
        class="flex items-center justify-between bg-white shadow-lg rounded-lg py-4 px-5 hover:bg-teal-50 transition-all duration-300"
      >
        <div class="flex items-center">
          <input
            type="checkbox"
            v-model="task.completed"
            @change="updateTask(task)"
            class="w-5 h-5 text-teal-500 border-2 border-teal-400 rounded-full focus:ring-0"
          />
          <span
            :class="{ 'line-through text-gray-400': task.completed }"
            class="ml-4 text-lg text-gray-700 font-medium"
          >
            {{ task.task }}
          </span>
        </div>
        <div class="flex gap-2">
          <button
            @click="openEditModal(task)"
            class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 text-sm focus:outline-none"
          >
            Edit
          </button>
          <button
            @click="deleteTask(task.id)"
            class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 text-sm focus:outline-none"
          >
            Delete
          </button>
        </div>
      </li>
    </ul>

    <!-- Modal for Editing a Task -->
    <div v-if="isModalVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg p-6 w-96 shadow-xl">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Update Task</h3>
        <input
          v-model="editedTask"
          type="text"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
        />
        <div class="flex justify-end gap-4 mt-6">
          <button
            @click="confirmEditTask"
            class="px-6 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600"
          >
            Save
          </button>
          <button
            @click="closeModal"
            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      tasks: [],
      newTask: "",
      editedTask: "",
      taskToEdit: null,
      isModalVisible: false,
    };
  },
  mounted() {
    this.loadTasksFromLocalStorage();
    this.fetchTasks();
  },
  methods: {
    loadTasksFromLocalStorage() {
      const storedTasks = localStorage.getItem("tasks");
      if (storedTasks) {
        this.tasks = JSON.parse(storedTasks);
      }
    },

    saveTasksToLocalStorage() {
      localStorage.setItem("tasks", JSON.stringify(this.tasks));
    },

    fetchTasks() {
      axios
        .get("/api/tasks")
        .then((response) => {
          this.tasks = response.data;
          this.saveTasksToLocalStorage();
        })
        .catch((error) => {
          console.error("Error fetching tasks:", error);
        });
    },

    addTask() {
      if (!this.newTask.trim()) {
        return;
      }

      const newTask = {
        id: Date.now(),
        task: this.newTask,
        completed: false,
      };

      this.tasks.push(newTask);
      this.saveTasksToLocalStorage();
      this.newTask = "";

      axios
        .post("/api/tasks", { task: newTask.task })
        .then((response) => {
          newTask.id = response.data.id;
          this.saveTasksToLocalStorage();
        })
        .catch((error) => {
          console.error("Error adding task to backend:", error);
        });
    },

    updateTask(task) {
      this.saveTasksToLocalStorage();

      axios
        .patch(`/api/tasks/${task.id}`, { completed: task.completed })
        .catch((error) => {
          console.error("Error updating task:", error);
        });
    },

    deleteTask(id) {
      this.tasks = this.tasks.filter((task) => task.id !== id);
      this.saveTasksToLocalStorage();

      axios
        .delete(`/api/tasks/${id}`)
        .catch((error) => {
          console.error("Error deleting task:", error);
        });
    },

    openEditModal(task) {
      this.editedTask = task.task;
      this.taskToEdit = task;
      this.isModalVisible = true;
    },

    closeModal() {
      this.isModalVisible = false;
      this.editedTask = "";
      this.taskToEdit = null;
    },

    confirmEditTask() {
      if (this.editedTask.trim() && this.taskToEdit) {
        const updatedTask = {
          ...this.taskToEdit,
          task: this.editedTask.trim(),
        };

        this.taskToEdit.task = updatedTask.task;
        this.saveTasksToLocalStorage();

        axios
          .patch(`/api/tasks/${updatedTask.id}`, { task: updatedTask.task })
          .then(() => {
            this.saveTasksToLocalStorage();
          })
          .catch((error) => {
            console.error("Error updating task in backend:", error);
          });

        this.closeModal();
      }
    },
  },
};
</script>
