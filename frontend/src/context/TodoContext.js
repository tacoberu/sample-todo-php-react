import React, { createContext, useState } from 'react';


export const TodoContext = createContext();

export const TodoListProvider = ({ children }) => {
	const [todos, setTodos] = useState([]);
	const [todo, setTodo] = useState({});

	const updateTodos = (xs) => {
		setTodos(xs)
	}

	const addTodo = (x) => {
		setTodos([...todos, x]);
	}

	const updateTodo = (x) => {
		setTodo(x);
	}

	const removeTodoById = (id) => {
		const newTodos = todos.filter((x) => x.id !== id);
		setTodos(newTodos);
	}

	return (
		<TodoContext.Provider value={{ todos, todo, updateTodos, updateTodo, removeTodoById, addTodo }}>
			{children}
		</TodoContext.Provider>
	);
}
