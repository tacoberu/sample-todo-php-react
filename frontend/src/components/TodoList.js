import React, {useContext, useEffect} from 'react';
import TodoTableRow from "./TodoTableRow";
import { TodoContext } from "../context/TodoContext";
import { findTodos } from "../services/ApiService"
import {NavLink} from "react-router-dom";


export default function TodoList() {

	const { todos, updateTodos } = useContext(TodoContext);

	useEffect(() => {
		async function fetchData() {
			try {
				const xs = await findTodos();
				updateTodos(xs);
			}
			catch (error) {
				console.error('Error fetching todos:', error);
			}
		}

		fetchData();
	}, []);

	return(
		<div>
			<nav aria-label="breadcrumb">
				<ol className="breadcrumb">
					<li className="breadcrumb-item">
						<NavLink to="/">Todos</NavLink>
					</li>
				</ol>
			</nav>
			<table className="table table-striped">
				<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Title</th>
					<th scope="col">Price</th>
					<th scope="col">Quantity</th>
					<th scope="col">Actions</th>
				</tr>
				</thead>
				<tbody>
				{todos.map(x => <TodoTableRow key={x.id} {...x} />)}
				</tbody>
			</table>
			<div>
				<NavLink className="btn btn-primary" to="/new">Add</NavLink>
			</div>
		</div>

	);

}
