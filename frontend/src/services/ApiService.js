import axios from "axios";


const baseURL = process.env.REACT_APP_API_BASE_URL;
const apiUrl = `${baseURL}/api/todo`;


export const findTodos = async () => {
	try {
		const response = await axios.get(apiUrl);
		return response.data;
	}
	catch (error) {
		throw error;
	}
}


export const createTodo = async (entity) => {
	try {
		const response = await axios.post(apiUrl, entity);
		return response.data;
	}
	catch (error) {
		throw error;
	}
}


export const getTodoById = async (id) => {
	try {
		const response = await axios.get(`${apiUrl}/${id}`);
		return response.data;
	}
	catch (error) {
		throw error;
	}
}


export const updateTodoById = async (id, entity) => {
	try {
		const response = await axios.put(`${apiUrl}/${id}`, entity);
		return response.data;
	}
	catch (error) {
		throw error;
	}
}


export const deleteTodoById = async (id) => {
	try {
		const response = await axios.delete(`${apiUrl}/${id}`);
		return response.data;
	}
	catch (error) {
		throw error;
	}
}
