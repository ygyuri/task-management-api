import { defineStore } from "pinia";
import ApiService from "@/services/ApiService";
import { ref, computed } from "vue";

import Toast from "@/components/Toast.vue"; // Import toast component

export const useTaskStore = defineStore("taskStore", () => {
    const tasks = ref([]); // All tasks
    const trashedTasks = ref([]); // Soft-deleted tasks
    const task = ref(null); // Single task
    const loading = ref(false); // Loading state
    const pagination = ref({ page: 1, pageSize: 10, total: 0 });
  const filters = ref({ email: "", search: "", sortField: "created_at", sortOrder: "desc" });
    let toastRef = null; // Store reference for toast

     // Attach toast reference
     const setToastRef = (toast) => {
        toastRef = toast;
    };


   

    // ✅ **Fetch All Tasks**
    const fetchTasks = async (params = {}) => {
        try {
            loading.value = true;
            const response = await ApiService.get("/tasks", { params });
    
            console.log("API Response:", response); // Debugging
            tasks.value = response.data.data;
            pagination.value.total = response.data.total;

        } catch (error) {
            console.error("Error fetching tasks:", error);
            toastRef.value?.showToast("Failed to fetch tasks!", "error");
        } finally {
            loading.value = false;
        }
    };
    
    

    // ✅ **Fetch Trashed Tasks**
    const fetchTrashedTasks = async () => {
        try {
            loading.value = true;
            const response = await ApiService.get("/tasks/trashed");
            trashedTasks.value = response.data.data;;
        } catch (error) {
            console.error("Error fetching trashed tasks:", error);
        } finally {
            loading.value = false;
        }
    };


    // ✅ Fetch Single Task (Vuex / Pinia Store or Reactive Store)
    const fetchTask = async (id) => {
        try {
            loading.value = true;
            const response = await ApiService.get(`/tasks/${id}`);

            console.log("Full API Response:", response); // Debugging
            console.log("Response Data:", response?.data); // Debugging

            if (response?.data?.status === "success" && response?.data?.data) {
                task.value = response.data.data; // ✅ Correctly extract the task object
                console.log("Fetched Task:", task.value);
            } else {
                console.warn("Task fetch failed:", response);
                task.value = null; // Reset task if fetch fails
            }
        } catch (error) {
            console.error(`Error fetching task ${id}:`, error);
            task.value = null;
        } finally {
            loading.value = false;
        }
    };

    

    // ✅ **Create a Task**
   // Create Task
   const createTask = async (newTask) => {
    try {
        const response = await ApiService.post("/tasks", newTask);

        // ✅ Instead of manually pushing, refetch all tasks
        await fetchTasks();

        // ✅ Show success toast
        if (toastRef) toastRef.showToast("Task created successfully!", "success");
    } catch (error) {
        console.error("❌ Error creating task:", error);
        if (toastRef) toastRef.showToast("Failed to create task!", "error");
    }
};


    

// ✅ **Update a Task**
const updateTask = async (id, updatedTask) => {
    try {
        const response = await ApiService.put(`/tasks/${id}`, updatedTask);

        // Find the updated task in the list and update it
        const index = tasks.value.findIndex((t) => t.id === id);
        if (index !== -1) {
            tasks.value[index] = response.data; // ✅ Ensure correct response handling
        }

        // Refresh tasks list
        await fetchTasks();

      
    } catch (error) {
        console.error(`Error updating task ${id}:`, error);

       
    }
};

    // ✅ **Soft Delete a Task**
    const deleteTask = async (id) => {
        try {
            await ApiService.delete(`/tasks/${id}`);
            tasks.value = tasks.value.filter((t) => t.id !== id);

            // Show delete toast
            if (toastRef) toastRef.showToast("Task moved to trash!", "error");
        } catch (error) {
            console.error(`❌ Error deleting task ${id}:`, error);
        }
    };

    // ✅ **Restore a Task**
    const restoreTask = async (id) => {
        try {
            const response = await ApiService.post(`/tasks/${id}/restore`);
            trashedTasks.value = trashedTasks.value.filter((t) => t.id !== id);
            tasks.value.push(response);
            useNotification.notify({ type: "success", text: "Task restored successfully!" });
        } catch (error) {
            console.error(`Error restoring task ${id}:`, error);
        }
    };
    

    return {
        tasks,
        trashedTasks,
        task,
        loading,
        fetchTasks,
        fetchTrashedTasks,
        fetchTask,
        createTask,
        updateTask,
        deleteTask,
        restoreTask,
        totalTasks: computed(() => tasks.value.length),
        totalTrashedTasks: computed(() => trashedTasks.value.length),
    };
});
