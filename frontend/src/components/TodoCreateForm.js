import React, {useContext, useRef} from "react";
import {createTodo} from "../services/ApiService";
import {useNavigate} from'react-router-dom';
import {TodoContext} from "../context/TodoContext";


export default function TodoCreateForm() {

	const navigate = useNavigate();
	const titleRef = useRef();
	const descriptionRef = useRef();
	const statusRef = useRef();

	const {addTodo} = useContext(TodoContext);

	async function add(target) {
		target.preventDefault();

		try {
			const newTodo = {
				title: titleRef.current.value,
				description: descriptionRef.current.value,
				status: statusRef.current.value
			};

			console.log(newTodo)

			const response = await createTodo(newTodo);
			console.log(response)
			addTodo(response);
			navigate(`/${response.id}`);
		}
		catch (error) {
			console.error('Error', error);
		}
	}

	return(
		<form>
			<div className="mb-3 mt-5">
				<div className="row">
					<div className="col-6">
						<label htmlFor="title" className="form-label">Title</label>
						<input ref={titleRef} type="text" className="form-control" id="title" aria-describedby="titleHelp" />
						<div id="titleHelp" className="form-text">Input the product title here.</div>
					</div>
					<div className="col-6">
						<label htmlFor="status" className="form-label">Status</label>
						<select ref={statusRef} className="form-control" id="status">
							<option> -- </option>
							<option value="pending">Pending</option>
							<option value="completed">Completed</option>
						</select>
					</div>
				</div>
			</div>

			<div className="mb-3">
				<label htmlFor="description" className="form-label">Description</label>
				<input ref={descriptionRef} type="text" className="form-control" id="description" />
			</div>

			<button onClick={add} type="submit" className="btn btn-primary">Add</button>
		</form>
	);

}
