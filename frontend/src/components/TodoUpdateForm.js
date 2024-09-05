import React, {useContext, useEffect, useRef} from "react";
import {getTodoById, updateTodoById} from "../services/ApiService";
import {NavLink, useNavigate, useParams} from 'react-router-dom';
import {TodoContext} from "../context/TodoContext";


export default function TodoUpdateForm() {

	const { id } = useParams();
	const navigate = useNavigate();
	const {todo, updateTodo} = useContext(TodoContext);

	async function update(target) {
		target.preventDefault();

		try {
			const response = await updateTodoById(id, todo);
			navigate(`/${response.id}`);
		}
		catch (error) {
			console.error('Error', error);
		}
	}

	useEffect(() => {

		async function fetchData() {
			try {
				const x = await getTodoById(id);
				updateTodo(x);
			}
			catch (error) {
				console.error('Error fetching todos:', error);
			}
		}

		fetchData();
	}, []);

	const handleChange = (event) => {
		const { id, value } = event.target;

		switch (id) {
			case "title":
				updateTodo({...todo, title: value});
				break;
			case "description":
				updateTodo({...todo, description: value});
				break;
			case "status":
				updateTodo({...todo, status: value});
				break;
			default:
				break;
		}
	};

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
					<li className="breadcrumb-item active">
						<NavLink className={({ isActive }) => (isActive ? 'active' : '')} to={`/${id}/edit`}>edit</NavLink>
					</li>
				</ol>
			</nav>
			<form>
				<div className="mb-3 mt-5">
					<div className="row">
						<div className="col-6">
							<label htmlFor="title" className="form-label">Title</label>
							<input onChange={handleChange} value={todo?.title || ''} type="text" className="form-control" id="title" aria-describedby="titleHelp" />
							<div id="titleHelp" className="form-text">Input the todo title here.</div>
						</div>
						<div className="col-6">
							<label htmlFor="status" className="form-label">Status</label>
							<select onChange={handleChange} value={todo?.status || 0} className="form-control" id="status">
								<option> -- </option>
								<option value="pending">Pending</option>
								<option value="completed">Completed</option>
							</select>
						</div>
					</div>
				</div>
				<div className="mb-3">
					<label htmlFor="description" className="form-label">Description</label>
					<input onChange={handleChange} value={todo?.description || ''} type="text" className="form-control" id="description" />
				</div>

				<button onClick={update} type="submit" className="btn btn-primary">Update</button>
			</form>
		</div>
	);

}
