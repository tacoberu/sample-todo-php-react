import { NavLink } from "react-router-dom";
import {deleteTodoById} from "../services/ApiService";
import {useContext} from 'react';
import {TodoContext} from "../context/TodoContext";


export default function TodoTableRow ({id, title, description, status}) {

	const {removeTodoById} = useContext(TodoContext);

	async function deleteTodo() {
		try {
			await deleteTodoById(id);
			removeTodoById(id);
		}
		catch (error) {
			console.error('Error fetching todos:', error);
		}
	}

	return(
		<tr>
			<th scope="row">{id}</th>
			<td>{title}</td>
			<td>{description}</td>
			<td>{status}</td>
			<td>
				<div className="btn-group">
					<NavLink className="btn btn-info" to={`/${id}`}>View</NavLink>
					<NavLink className="btn btn-light" to={`/${id}/edit`}>Edit</NavLink>
					<button onClick={deleteTodo} className="btn btn-danger">Delete</button>
				</div>
			</td>
		</tr>
	);
}
