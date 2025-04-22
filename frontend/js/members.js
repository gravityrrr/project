document.addEventListener('DOMContentLoaded', async () => {
  if (!checkAuth()) return; // From auth.js
  
  // Initialize UI elements
  const addMemberBtn = document.getElementById('addMemberBtn');
  const memberModal = document.getElementById('memberModal');
  const confirmModal = document.getElementById('confirmModal');
  const memberForm = document.getElementById('memberForm');
  const modalTitle = document.getElementById('modalTitle');
  const cancelBtn = document.getElementById('cancelBtn');
  const closeModalBtns = document.querySelectorAll('.close');
  const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
  
  let deleteId = null; // ID of the member to be deleted
  
  // Load members
  await loadMembers();
  
  // Load packages for dropdown
  await loadPackages();
  
  // Event listeners
  if (addMemberBtn) {
    addMemberBtn.addEventListener('click', () => {
      openAddMemberModal();
    });
  }
  
  if (memberForm) {
    memberForm.addEventListener('submit', (e) => {
      e.preventDefault();
      saveMember();
    });
  }
  
  if (cancelBtn) {
    cancelBtn.addEventListener('click', () => {
      closeModal(memberModal);
    });
  }
  
  if (closeModalBtns) {
    closeModalBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        const modal = e.target.closest('.modal');
        closeModal(modal);
      });
    });
  }
  
  if (cancelDeleteBtn) {
    cancelDeleteBtn.addEventListener('click', () => {
      closeModal(confirmModal);
      deleteId = null;
    });
  }
  
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', () => {
      if (deleteId) {
        deleteMember(deleteId);
      }
    });
  }
});

// Load all members
async function loadMembers() {
  try {
    const members = await apiRequest('/api/members'); // From auth.js
    
    if (members) {
      displayMembers(members);
    }
  } catch (error) {
    console.error('Error loading members:', error);
  }
}

// Display members in the table
function displayMembers(members) {
  const tableBody = document.getElementById('membersTableBody');
  
  if (!tableBody) return;
  
  tableBody.innerHTML = '';
  
  if (members.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="8" class="text-center">No members found</td>
      </tr>
    `;
    return;
  }
  
  members.forEach(member => {
    const row = document.createElement('tr');
    
    row.innerHTML = `
      <td>${member.id}</td>
      <td>${member.name}</td>
      <td>${member.age || '-'}</td>
      <td>${member.gender || '-'}</td>
      <td>${member.phone || '-'}</td>
      <td>${member.package_name || '-'}</td>
      <td>${member.end_date ? new Date(member.end_date).toLocaleDateString() : '-'}</td>
      <td>
        <div class="table-actions">
          <button class="btn-table-action btn-edit" data-id="${member.id}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
          </button>
          <button class="btn-table-action btn-delete" data-id="${member.id}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
              <line x1="10" y1="11" x2="10" y2="17"></line>
              <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
          </button>
        </div>
      </td>
    `;
    
    tableBody.appendChild(row);
  });
  
  // Add event listeners to edit and delete buttons
  const editButtons = document.querySelectorAll('.btn-edit');
  const deleteButtons = document.querySelectorAll('.btn-delete');
  
  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      openEditMemberModal(id, members);
    });
  });
  
  deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      openDeleteConfirmation(id);
    });
  });
}

// Load packages for dropdown
async function loadPackages() {
  try {
    const packages = await apiRequest('/api/packages'); // From auth.js
    
    if (packages) {
      const packageSelect = document.getElementById('package');
      
      if (packageSelect) {
        // Clear existing options except the first one
        packageSelect.innerHTML = '<option value="">Select Package</option>';
        
        // Add packages
        packages.forEach(pkg => {
          const option = document.createElement('option');
          option.value = pkg.id;
          option.textContent = `${pkg.name} (${pkg.duration_weeks} weeks - $${pkg.price})`;
          packageSelect.appendChild(option);
        });
      }
    }
  } catch (error) {
    console.error('Error loading packages:', error);
  }
}

// Open add member modal
function openAddMemberModal() {
  const memberModal = document.getElementById('memberModal');
  const modalTitle = document.getElementById('modalTitle');
  const memberForm = document.getElementById('memberForm');
  const saveBtn = document.getElementById('saveBtn');
  
  // Clear form
  memberForm.reset();
  document.getElementById('memberId').value = '';
  
  // Set modal title
  modalTitle.textContent = 'Add New Member';
  saveBtn.textContent = 'Save Member';
  
  // Set today's date as default start date
  const today = new Date();
  const formattedDate = today.toISOString().split('T')[0];
  document.getElementById('startDate').value = formattedDate;
  
  // Open modal
  openModal(memberModal);
}

// Open edit member modal
function openEditMemberModal(id, members) {
  const member = members.find(m => m.id == id);
  
  if (!member) return;
  
  const memberModal = document.getElementById('memberModal');
  const modalTitle = document.getElementById('modalTitle');
  const saveBtn = document.getElementById('saveBtn');
  
  // Set form values
  document.getElementById('memberId').value = member.id;
  document.getElementById('name').value = member.name || '';
  document.getElementById('age').value = member.age || '';
  document.getElementById('gender').value = member.gender || '';
  document.getElementById('phone').value = member.phone || '';
  document.getElementById('email').value = member.email || '';
  document.getElementById('address').value = member.address || '';
  
  if (member.package_id) {
    document.getElementById('package').value = member.package_id;
  } else {
    document.getElementById('package').value = '';
  }
  
  if (member.start_date) {
    document.getElementById('startDate').value = new Date(member.start_date).toISOString().split('T')[0];
  } else {
    // Set today's date as default
    const today = new Date();
    document.getElementById('startDate').value = today.toISOString().split('T')[0];
  }
  
  // Set modal title
  modalTitle.textContent = 'Edit Member';
  saveBtn.textContent = 'Update Member';
  
  // Open modal
  openModal(memberModal);
}

// Open delete confirmation modal
function openDeleteConfirmation(id) {
  const confirmModal = document.getElementById('confirmModal');
  deleteId = id;
  openModal(confirmModal);
}

// Save or update member
async function saveMember() {
  const memberId = document.getElementById('memberId').value;
  const name = document.getElementById('name').value;
  const age = document.getElementById('age').value;
  const gender = document.getElementById('gender').value;
  const phone = document.getElementById('phone').value;
  const email = document.getElementById('email').value;
  const address = document.getElementById('address').value;
  const packageId = document.getElementById('package').value;
  const startDate = document.getElementById('startDate').value;
  
  const memberData = {
    name,
    age,
    gender,
    phone,
    email,
    address
  };
  
  if (packageId) {
    memberData.package_id = packageId;
    memberData.start_date = startDate;
  }
  
  try {
    let response;
    
    if (memberId) {
      // Update existing member
      response = await apiRequest(`/api/members/${memberId}`, 'PUT', memberData);
    } else {
      // Create new member
      response = await apiRequest('/api/members', 'POST', memberData);
    }
    
    if (response) {
      // Close modal and reload members
      const memberModal = document.getElementById('memberModal');
      closeModal(memberModal);
      loadMembers();
    }
  } catch (error) {
    console.error('Error saving member:', error);
  }
}

// Delete member
async function deleteMember(id) {
  try {
    const response = await apiRequest(`/api/members/${id}`, 'DELETE');
    
    if (response) {
      // Close modal and reload members
      const confirmModal = document.getElementById('confirmModal');
      closeModal(confirmModal);
      deleteId = null;
      loadMembers();
    }
  } catch (error) {
    console.error('Error deleting member:', error);
  }
}

// Open modal
function openModal(modal) {
  if (modal) {
    modal.classList.add('show');
  }
}

// Close modal
function closeModal(modal) {
  if (modal) {
    modal.classList.remove('show');
  }
}