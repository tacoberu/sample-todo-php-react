import React, {useEffect, useContext} from "react";
import {NavLink, useParams, useNavigate} from "react-router-dom";
import {deleteTodoById, getTodoById} from "../services/ApiService";
import {TodoContext} from "../context/TodoContext";

export default function TodoDetail() {

	const { id } = useParams();
	const { todo, updateTodo, removeTodoById } = useContext(TodoContext);
	const navigate = useNavigate();

	useEffect(() => {

		async function fetchData() {
			try {
				const todo = await getTodoById(id);
				updateTodo(todo);
			}
			catch (error) {
				console.error('Error fetching todos:', error);
			}
		}

		fetchData();
	}, []);

	async function deleteTodo() {
		try {
			await deleteTodoById(id);
			removeTodoById(id);
			navigate("/");
		}
		catch (error) {
			console.error('Error fetching todos:', error);
		}
	}

	return(
		<div>
			<nav aria-label="breadcrumb">
				<ol className="breadcrumb">
					<li className="breadcrumb-item">
						<NavLink to="/">Todos</NavLink>
					</li>
					<li className="breadcrumb-item">
						<NavLink className={({ isActive }) => (isActive ? 'active' : '')} to={`/${id}`}>{id}</NavLink>
					</li>
				</ol>
			</nav>
			<h4 className="text-center mb-5 mt-5">Todo Info: {id}</h4>
			<table className="table">
				<tbody>
				<tr>
					<th scope="row">Title</th>
					<td>{todo.title}</td>
				</tr>
				<tr>
					<th scope="row">Description</th>
					<td>{todo.description}</td>
				</tr>
				<tr>
					<th scope="row">Status</th>
					<td>{todo.status}</td>
				</tr>
				</tbody>
			</table>
			<div>
				<div className="row">
					<div className="col-6">
						<NavLink className="btn btn-light" to={`/${id}/edit`}>Edit</NavLink>
					</div>
					<div className="col-6 text-end">
						<button onClick={deleteTodo} className="btn btn-danger pull-right">Delete</button>
					</div>
				</div>
			</div>
		</div>
	);

}
