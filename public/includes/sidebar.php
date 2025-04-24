
<aside class="w-64 fixed top-0 left-0 h-full bg-white border-r border-gray-200"></aside>
    <div class="p-5 border-b border-gray-200 h-16 flex items-center">
        <div class="flex gap-2 items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600"><path d="M22 12 3 20l3.66-10L3 4l19 8Z"></path><circle cx="15" cy="12" r="2"></circle></svg>
            <h1 class="font-bold text-xl text-gray-800">FitFusion</h1>
        </div>
    </div>
    <div class="flex flex-col gap-1 p-3 flex-grow overflow-y-auto">
        <a href="index.php" class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo ($_SERVER['PHP_SELF'] == '/index.php') ? 'text-indigo-600' : 'text-gray-500'; ?>"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <span>Dashboard</span>
        </a>
        <a href="members.php" class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo ($_SERVER['PHP_SELF'] == '/members.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo ($_SERVER['PHP_SELF'] == '/members.php') ? 'text-indigo-600' : 'text-gray-500'; ?>"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Members</span>
        </a>
        <a href="trainers.php" class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo ($_SERVER['PHP_SELF'] == '/trainers.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo ($_SERVER['PHP_SELF'] == '/trainers.php') ? 'text-indigo-600' : 'text-gray-500'; ?>"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span>Trainers</span>
        </a>
        <a href="packages.php" class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo ($_SERVER['PHP_SELF'] == '/packages.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo ($_SERVER['PHP_SELF'] == '/packages.php') ? 'text-indigo-600' : 'text-gray-500'; ?>"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
            <span>Packages</span>
        </a>
        <a href="attendance.php" class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo ($_SERVER['PHP_SELF'] == '/attendance.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo ($_SERVER['PHP_SELF'] == '/attendance.php') ? 'text-indigo-600' : 'text-gray-500'; ?>"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line><path d="m9 16 2 2 4-4"></path></svg>
            <span>Attendance</span>
        </a>
        <a href="equipment.php" class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo ($_SERVER['PHP_SELF'] == '/equipment.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo ($_SERVER['PHP_SELF'] == '/equipment.php') ? 'text-indigo-600' : 'text-gray-500'; ?>"><circle cx="12" cy="12" r="10"></circle><path d="m4.93 4.93 4.24 4.24"></path><path d="m14.83 9.17 4.24-4.24"></path><path d="m14.83 14.83 4.24 4.24"></path><path d="m9.17 14.83-4.24 4.24"></path><circle cx="12" cy="12" r="4"></circle></svg>
            <span>Equipment</span>
        </a>
        <a href="settings.php" class="flex items-center gap-3 px-3 py-2 rounded-md <?php echo ($_SERVER['PHP_SELF'] == '/settings.php') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-100'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo ($_SERVER['PHP_SELF'] == '/settings.php') ? 'text-indigo-600' : 'text-gray-500'; ?>"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <span>Settings</span>
        </a>
    </div>
    <div class="p-3 border-t border-gray-200">
        <a href="logout.php" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            <span>Logout</span>
        </a>
    </div>
</aside>
