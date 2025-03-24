<template>
    <div class="task-manager">
        <h2>Task Manager</h2>

      
    <div class="filters-container">
      <!-- Existing Filters -->
      <div class="filter-group">
        <label for="email-filter">Email:</label>
        <Input v-model:value="filters.email" id="email-filter" placeholder="Filter by Email" />
      </div>

      <div class="filter-group">
        <label for="search-filter">Search:</label>
        <Input v-model:value="filters.search" id="search-filter" placeholder="Search by Title" />
      </div>

      <div class="filter-group">
        <label for="priority-filter">Priority:</label>
        <Select v-model:value="filters.priority" id="priority-filter" placeholder="Filter by Priority">
          <Select.Option value="low">Low</Select.Option>
          <Select.Option value="medium">Medium</Select.Option>
          <Select.Option value="high">High</Select.Option>
        </Select>
      </div>

      <div class="filter-group">
        <label for="due-date-filter">Due Date:</label>
        <DatePicker v-model:value="filters.dueDate" id="due-date-filter" format="YYYY-MM-DD" />
      </div>

      <div class="filter-group">
        <label for="sort-field">Sort By:</label>
        <Select v-model:value="filters.sortField" id="sort-field">
          <Select.Option value="created_at">Created At</Select.Option>
          <Select.Option value="title">Title</Select.Option>
          <Select.Option value="status">Status</Select.Option>
        </Select>
      </div>

      <div class="filter-group">
        <label for="sort-order">Order:</label>
        <Select v-model:value="filters.sortOrder" id="sort-order">
          <Select.Option value="asc">Ascending</Select.Option>
          <Select.Option value="desc">Descending</Select.Option>
        </Select>
      </div>

      <!-- Apply Filters Button -->
      <div class="filter-group">
        <Button type="primary" @click="applyFilters">Apply Filters</Button>
      </div>
    </div>
        <!-- Add Task Button -->
        <Button type="primary" @click="showModal = true">Add Task</Button>

        <!-- Task Creation Modal -->
        <Modal :visible="showModal" title="Create Task" @ok="handleCreateTask" @cancel="showModal = false">
    <Form :model="newTask" ref="taskFormRef" :rules="rules" layout="vertical">
        <Form.Item label="Title" name="title">
            <Input v-model:value="newTask.title" placeholder="Task Title" />
        </Form.Item>
        <Form.Item label="Description" name="description">
            <Input.TextArea v-model:value="newTask.description" placeholder="Description" />
        </Form.Item>
        <Form.Item label="Priority" name="priority">
            <Select v-model:value="newTask.priority" placeholder="Select Priority">
                <Select.Option value="low">Low</Select.Option>
                <Select.Option value="medium">Medium</Select.Option>
                <Select.Option value="high">High</Select.Option>
            </Select>
        </Form.Item>
        <Form.Item label="Due Date" name="due_date">
            <DatePicker v-model:value="newTask.due_date" format="YYYY-MM-DD" />
        </Form.Item>
        <Form.Item label="Email" name="email">
            <Input v-model:value="newTask.email" placeholder="Email" />
        </Form.Item>
    </Form>
</Modal>

<!-- View Task Modal -->
<Modal
      :visible="viewTaskModalVisible"
      title="Task Details"
      @cancel="viewTaskModalVisible = false"
      :footer="null"
      width="600"
    >
      <div v-if="task" class="task-details-modal">
        <h3>Task Details</h3>
        <div class="detail-row">
          <span class="detail-label">Title:</span>
          <span class="detail-value">{{ task.title }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Description:</span>
          <span class="detail-value">{{ task.description }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Priority:</span>
          <span class="detail-value">{{ task.priority }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Due Date:</span>
          <span class="detail-value">
            {{ new Date(task.due_date).toLocaleDateString() }}
          </span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Email:</span>
          <span class="detail-value">{{ task.user_email }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Created At:</span>
          <span class="detail-value">
            {{ new Date(task.created_at).toLocaleDateString() }}
          </span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Updated At:</span>
          <span class="detail-value">
            {{ new Date(task.updated_at).toLocaleDateString() }}
          </span>
        </div>
        </div>
      <p v-else>Loading task details...</p>
    </Modal>

    
        <!-- Custom Task Update Modal -->
        <div v-if="updateModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>{{ isViewMode ? 'Task Details' : 'Update Task' }}</h3>
                    <button @click="closeModal">✖</button>
                </div>
                <form @submit.prevent="handleUpdateTask">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input
                    v-model="selectedTask.title"
                    type="text"
                    id="title"
                    required
                    :disabled="isViewMode"
                    />
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea
                    v-model="selectedTask.description"
                    id="description"
                    :disabled="isViewMode"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select v-model="selectedTask.priority" id="priority" :disabled="isViewMode">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    </select>
                </div>

                <div class="form-group">
    <label for="due_date">Due Date</label>
    <input
        v-model="selectedTask.due_date"
        type="date"
        id="due_date"
        :disabled="isViewMode"
    />
</div>



                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                    v-model="selectedTask.user_email"
                    type="email"
                    id="email"
                    disabled
                    />
                </div>

                <div v-if="!isViewMode" class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <button type="button" class="btn btn-secondary" @click="closeModal">
                    Cancel
                    </button>
                </div>
                <div v-else class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">close</button>
                </div>
                </form>
            </div>
        </div>

 <!-- Task List Table -->
 <Table :dataSource="store.tasks" :loading="store.loading" :columns="columns" :pagination="false">
            <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'actions'">
                    <Space>
                        <Button @click="openUpdateModal(record, true)">View</Button>
                        <Button type="primary" @click="openUpdateModal(record)">Update</Button>
                        <Button type="danger" @click="handleDeleteTask(record.id)">Delete</Button>
                    </Space>
                </template>
            </template>
        </Table>

        <!-- Pagination -->
        <Pagination
            :current="currentPage"
            :total="totalTasks"
            :pageSize="pageSize"
            @update:current="handlePageChange"
        />
         <!-- Custom Toast Component -->
         <Toast ref="toastRef" />


    </div>

    
</template>

<script setup>
import { ref, watch, reactive, onMounted,  nextTick, computed } from "vue";
import { useTaskStore } from "@/stores/TaskStore";
import { useRouter } from "vue-router";
import { notification, Modal } from "ant-design-vue";
import {
    Table,
    Form,
    Input,
    Select,
    DatePicker,
    Button,
    Space,
    Pagination,
} from "ant-design-vue";
import Toast from "@/components/Toast.vue"; // Import custom toast component
import dayjs from "dayjs";

const task = ref(null);

const store = useTaskStore();
const router = useRouter();
const showModal = ref(false);
const taskFormRef = ref(null);
const toastRef = ref(null); // Reference for toast component
const viewModal = ref(false);
const selectedTask = ref({});
const updateModal = ref(false)
const viewTaskModalVisible = ref(false);
// Filters
const filters = reactive({
  email: "",
  search: "",
  priority: "",
  dueDate: null,
  sortField: "",
  sortOrder: ""
});

// Task Data
const newTask = ref({
    title: "",
    description: "",
    priority: "medium",
    due_date: "",
    email: "",
});

// Validation Rules
const rules = {
    title: [{ required: true, message: "Title is required!" }],
    priority: [{ required: true, message: "Priority is required!" }],
    email: [{ required: true, type: "email", message: "Valid email is required!" }],
    due_date: [{ required: true, type: "date", message: "Invalid date format!" }]
};

// Pagination
const currentPage = ref(1);
const pageSize = ref(10);
const totalTasks = computed(() => store.tasks.length);

const columns = [
    { title: "Title", dataIndex: "title", key: "title" },
    { title: "Description", dataIndex: "description", key: "description" },
    { title: "Priority", dataIndex: "priority", key: "priority" },
    {
    title: "Due Date",
    dataIndex: "due_date",
    key: "due_date",
    customRender: ({ text }) => dayjs(text).format("DD MMM YYYY"),
  },
    { title: "Email", dataIndex: "email", key: "email" },
    { title: "Actions", key: "actions" },
];

// Fetch tasks on mount
onMounted(() => {
    store.fetchTasks({ page: currentPage.value, per_page: pageSize.value });
});

const applyFilters = () => {
    const params = {
        page: currentPage.value,
        per_page: pageSize.value,
        email: filters.email,
        search: filters.search,
        priority: filters.priority,
        due_date: filters.dueDate ? filters.dueDate.format("YYYY-MM-DD") : null,
        sort_field: filters.sortField,
        sort_order: filters.sortOrder,
    };

    console.log("Fetching tasks with filters:", params); // Debugging
    store.fetchTasks(params);
};

// Fetch Tasks with Filters and Pagination
const fetchTasks = () => {
  const params = {
    page: currentPage.value,
    per_page: pageSize.value,
    email: filters.email,
    search: filters.search,
    priority: filters.priority,
    due_date: filters.dueDate ? filters.dueDate.format("YYYY-MM-DD") : null, // Safely access dueDate
    sort_field: filters.sortField,
    sort_order: filters.sortOrder,
  };

  store.fetchTasks(params);
  console.log("Fetching tasks with params:", JSON.stringify(params, null, 2)); // Detailed log
};


// Watch for filter changes
watch(
  filters,
  () => {
    currentPage.value = 1;
    fetchTasks();
    console.log("Filters changed:", JSON.stringify(filters.value, null, 2)); // Detailed log
  },
  { deep: true }
);

// Fetch tasks on mount
onMounted(() => {
    store.fetchTasks({ page: currentPage.value, per_page: pageSize.value });
});


// Handle Task Creation
const handleCreateTask = async () => {
    try {
        await taskFormRef.value.validate(); // Ensure fields are validated

        // Format task data
        const formattedTask = {
            ...newTask.value,
            due_date: newTask.value.due_date ? newTask.value.due_date.format("YYYY-MM-DD") : null,
            user_email: newTask.value.email,
        };

        // ✅ Create the task
        await store.createTask(formattedTask);

        // ✅ Refresh the task list after creation
        await store.fetchTasks();

        // ✅ Show success message
        toastRef.value.showToast("Task created successfully!", "success");

        // ✅ Reset the form
        taskFormRef.value.resetFields();
        newTask.value = { title: "", description: "", priority: "medium", due_date: null, email: "" };

        // ✅ Close the modal after success
        showModal.value = false;

    } catch (error) {
        toastRef.value.showToast("Validation failed! Please fix errors.", "error");
    }
};

watch(newTask, (newVal) => {
    console.log("📝 Task Form Updated:", newVal);
}, { deep: true });

// Open Update Modal
const openUpdateModal = (task, isView = false) => {
    selectedTask.value = { ...task };
    updateModal.value = true;
    isViewMode.value = isView; // Add a new ref for view mode
};

const isViewMode = ref(false); // Initialize isViewMode

// Handle Update Task
const handleUpdateTask = async () => {
    try {
        if (!selectedTask.value.title.trim()) {
            toastRef.value.showToast("Title is required!", "error"); // Corrected function call
            return;
        }

        await store.updateTask(selectedTask.value.id, {
            title: selectedTask.value.title,
            description: selectedTask.value.description,
            priority: selectedTask.value.priority,
            due_date: selectedTask.value.due_date,
            user_email: selectedTask.value.user_email,
        });

        updateModal.value = false;
        toastRef.value.showToast("Task updated successfully!", "success"); // Corrected function call
    } catch (error) {
        console.error("Error updating task:", error);
        toastRef.value.showToast("Failed to update task!", "error"); // Corrected function call
    }
};


// Close Modal
const closeModal = () => {
    updateModal.value = false;
};

// Handle Task Deletion
const handleDeleteTask = async (id) => {
    Modal.confirm({
        title: "Are you sure you want to delete this task?",
        content: "This task will be moved to the trash.",
        onOk: async () => {
            await store.deleteTask(id);
            toastRef.value.showToast("Task moved to trash!", "error");
        }
    });
};

// Pagination Handling
const handlePageChange = (page) => {
    currentPage.value = page;
    store.fetchTasks({ page, per_page: pageSize.value });
};

// View Task Details
const goToTaskDetails = (id) => {
    router.push(`/tasks/${id}`);
};
</script>
<style scoped>
.task-manager {
  width: 100%;
  padding: 20px;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(5px);
}

.modal-container {
  background: white;
  padding: 20px;
  border-radius: 8px; /* Slightly more rounded corners */
  width: 450px; /* Increased width for better field spacing */
  max-width: 95%; /* Responsive width */
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px; /* Add space below the header */
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px; /* Add space above the footer */
}

.form-group {
  margin-bottom: 15px; /* Increased margin for better spacing */
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600; /* Bold labels */
  color: #333; /* Darker label color */
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  box-sizing: border-box; /* Ensure padding is included in width */
}

.form-group textarea {
  resize: vertical; /* Allow vertical resizing */
}

.btn {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
}

.btn-primary {
  background-color: #1890ff;
  color: white;
}

.btn-secondary {
  background-color: #f0f0f0;
  color: #333;
}

.task-details-modal {
  padding: 20px;
}

.detail-row {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.detail-label {
  font-weight: bold;
  width: 120px;
  color: #333;
}

.detail-value {
  flex: 1;
  color: #555;
}

.filters-container {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
  flex-wrap: wrap;
  align-items: flex-end; /* Align items at the bottom */
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
  flex: 1 1 200px; /* Allow filters to grow and wrap */
}
.filter-group label {
  font-weight: 600;
  color: #333;
}

.filter-group input,
.filter-group select {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.apply-button {
  flex: 0 0 auto; /* Prevent the button from growing */
  align-self: flex-end; /* Align the button at the bottom */
}

.add-task-button {
  margin-bottom: 20px; /* Add space below the button */
}

</style>