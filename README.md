## Description
The system allows the upper-tier users (Managers, BSEs, Leaders) to assign tasks to members and then give feedback and ratings. After every month or every evaluation, the upper-tier users can generate the summary evaluation of the members based on their work and feedbacks with generated text from AI in PDF format.

## Run Notes

### Start Laravel
```
php artisan serve --host=0.0.0.0 --port=8000
```

### Start Vite
```
npm run dev
```

### Start Background Processes
```
php artisan queue:work
```

### To Run Background Task Manually
```
php artisan app:run-process-late-tasks
```


## Page Explanations
### User Page
The User Page allows upper-tier users to view and manage individual user profiles. This includes assigning tasks to members, viewing their task progress, and providing feedback and ratings.
### Task Page
The Task Page lists all the tasks assigned to the members. Users can filter tasks based on various criteria such as status (completed, in progress, pending) and time period. This page also allows users to update the status of tasks and view detailed information about each task.
### Dashboard
The Dashboard provides an overview of the tasks and their statuses. It includes summary statistics such as the number of completed, in-progress, and pending tasks. The dashboard also highlights any late tasks and provides options to generate summary evaluations in PDF format using Generative AI.

## ScreenShots

<img width="1680" alt="image" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/19cd13f9-eca2-4f27-9cdd-ebebb68b1270">

<img width="1680" alt="image" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/5cddb190-0be4-44ed-b119-25c4b87963a8">

<img width="1680" alt="Screenshot 2024-05-31 at 3 22 15 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/80fd8ee2-80c8-4193-ae0b-ba712147bdd2">

<img width="1680" alt="Screenshot 2024-05-31 at 3 22 47 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/f4861a6f-4cae-4b25-8903-635da8df94e9">

<img width="1680" alt="Screenshot 2024-05-31 at 3 23 02 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/8dfb883c-bc4c-49f0-ad68-60331119f871">

<img width="1680" alt="Screenshot 2024-05-31 at 3 23 24 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/26f84d9a-4309-4ee1-94cd-7f63412e2a87">

<img width="1680" alt="Screenshot 2024-05-31 at 3 23 41 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/019f9481-6acd-44b7-90d5-89cb31621e42">

<img width="1680" alt="Screenshot 2024-05-31 at 3 23 56 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/02495170-0e60-4c84-9421-c5ad98712154">

<img width="1680" alt="Screenshot 2024-05-31 at 3 24 09 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/440cc1dc-c516-4775-9289-008109a7959b">

<img width="1680" alt="Screenshot 2024-05-31 at 3 24 22 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/aa425cd7-13da-4764-a5f2-5a1e49741598">

<img width="1234" alt="Screenshot 2024-05-31 at 3 24 39 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/d1bb7781-e4ec-4f12-9a69-438a7e91436e">

<img width="1680" alt="Screenshot 2024-05-31 at 3 24 58 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/e8d6ae05-1476-4968-b2d6-e32c0f433be4">

<img width="1680" alt="Screenshot 2024-05-31 at 3 25 09 PM" src="https://github.com/mtm-naylinhtoo/devTeamProject/assets/97865794/8c154a5e-5ca3-4b49-9202-2e1f9909579c">
