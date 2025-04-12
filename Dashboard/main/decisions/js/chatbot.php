<script>
    function clearChat() {
        const conversation = document.getElementById('ai-conversation');
        // Keep only the initial bot message
        const initialMessage = conversation.querySelector('.ai-message.bot:first-child');
        conversation.innerHTML = '';
        if (initialMessage) {
            conversation.appendChild(initialMessage);
        }
        // Scroll to bottom
        conversation.scrollTop = conversation.scrollHeight;
    }

    // Performance Chart
    const performanceCtx = document.getElementById('performanceCanvas').getContext('2d');
    const performanceChart = new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'University Performance',
                data: [75, 78, 82, 80, 85, 83, 87, 85, 88, 86, 89, 91],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'white',
                pointBorderColor: '#4CAF50',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'white',
                    titleColor: '#4CAF50',
                    bodyColor: '#333',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 12,
                    boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 70,
                    max: 100,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Department Distribution Chart
    const departmentCtx = document.getElementById('departmentCanvas').getContext('2d');
    const departmentChart = new Chart(departmentCtx, {
        type: 'pie',
        data: {
            labels: ['Academic', 'Administrative', 'Support Staff', 'Management', 'Research'],
            datasets: [{
                data: [45, 20, 15, 10, 10],
                backgroundColor: [
                    '#4CAF50',
                    '#4CAF50',
                    '#fdd835',
                    '#e74c3c',
                    '#9b59b6'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 13
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'white',
                    titleColor: '#4CAF50',
                    bodyColor: '#333',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 12,
                    boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + '%';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // AI Assistant Functionality
    const conversation = document.getElementById('ai-conversation');
    const aiForm = document.getElementById('ai-form');
    const aiInput = document.getElementById('ai-input');
    const sendBtn = document.getElementById('ai-send-btn');
    const chatStorageKey = 'hr_chat_messages';
    const sessionId = '<?php echo session_id(); ?>';

    // Staff data for the AI to reference
    const staffData = [
        <?php foreach ($top_performing_staff as $staff):
            $isPromotionCandidate = $staff['performance_score'] >= 80 && $staff['years_of_experience'] >= 3;
        ?> {
                staff_id: '<?php echo $staff['staff_id']; ?>',
                first_name: '<?php echo $staff['first_name']; ?>',
                last_name: '<?php echo $staff['last_name']; ?>',
                name: '<?php echo $staff['first_name'] . ' ' . $staff['last_name']; ?>',
                department: '<?php echo $staff['department_name']; ?>',
                position: '<?php echo $staff['role_name']; ?>',
                performance: <?php echo $staff['performance_score']; ?>,
                experience: <?php echo $staff['years_of_experience']; ?>,
                publications: <?php echo $staff['publication_count']; ?>,
                grants: <?php echo $staff['grant_count']; ?>,
                isPromotionCandidate: <?php echo $isPromotionCandidate ? 'true' : 'false'; ?>,
                lastEvaluation: '<?php echo date('M Y', strtotime('-' . rand(1, 12) . ' months')); ?>'
            },
        <?php endforeach; ?>
    ];

    // Function to save messages to localStorage
    function saveMessagesToLocalStorage() {
        const messages = Array.from(conversation.querySelectorAll('.ai-message')).map(msg => ({
            html: msg.innerHTML,
            isUser: msg.classList.contains('user'),
            isBot: msg.classList.contains('bot')
        }));
        localStorage.setItem(chatStorageKey, JSON.stringify(messages));
    }

    // Function to load messages from localStorage
    function loadMessagesFromLocalStorage() {
        const savedMessages = localStorage.getItem(chatStorageKey);
        if (savedMessages) {
            try {
                const messages = JSON.parse(savedMessages);
                conversation.innerHTML = '';
                messages.forEach(msg => {
                    addMessage(msg.html, msg.isUser);
                });
            } catch (e) {
                console.error('Error loading chat messages:', e);
            }
        }
    }

    // Function to save messages to server via AJAX
    function saveMessagesToServer() {
        const messages = Array.from(conversation.querySelectorAll('.ai-message')).map(msg => ({
            content: msg.textContent.trim(),
            is_user: msg.classList.contains('user') ? 1 : 0,
            timestamp: new Date().toISOString()
        }));

        fetch('../approve/save_chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                session_id: sessionId,
                messages: messages
            })
        }).catch(error => {
            console.error('Error saving chat to server:', error);
        });
    }

    // Function to load messages from server
    function loadMessagesFromServer() {
        fetch(`../approve/load_chat.php?session_id=${sessionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    conversation.innerHTML = '';
                    data.messages.forEach(msg => {
                        addMessage(msg.content, msg.is_user === 1);
                    });
                } else {
                    // If no server messages, check localStorage
                    loadMessagesFromLocalStorage();
                }
            })
            .catch(error => {
                console.error('Error loading chat from server:', error);
                // Fallback to localStorage if server fails
                loadMessagesFromLocalStorage();
            });
    }

    // Combined save function
    function saveMessages() {
        saveMessagesToLocalStorage();
        saveMessagesToServer();
    }

    // Combined load function
    function loadMessages() {
        loadMessagesFromServer();
    }

    // Clear chat from both storage locations
    function clearChat() {
        // Clear UI
        const initialMessage = conversation.querySelector('.ai-message.bot:first-child');
        conversation.innerHTML = '';
        if (initialMessage) {
            conversation.appendChild(initialMessage);
        }

        // Clear localStorage
        localStorage.removeItem(chatStorageKey);

        // Clear server storage
        fetch('../approve/clear_chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                session_id: sessionId
            })
        }).catch(error => {
            console.error('Error clearing chat from server:', error);
        });

        conversation.scrollTop = conversation.scrollHeight;
    }

    function addMessage(text, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `ai-message ${isUser ? 'user' : 'bot'}`;
        messageDiv.innerHTML = text;
        conversation.appendChild(messageDiv);
        conversation.scrollTop = conversation.scrollHeight;
        return messageDiv;
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'ai-message bot typing';
        typingDiv.innerHTML = '<div class="ai-typing-indicator"><span></span><span></span><span></span></div>';
        conversation.appendChild(typingDiv);
        conversation.scrollTop = conversation.scrollHeight;
        return typingDiv;
    }

    function removeTypingIndicator(typingDiv) {
        typingDiv.remove();
    }

    function getAIResponse(userInput) {
        const input = userInput.toLowerCase();

        // Promotion queries
        if (input.includes('promot') || input.includes('advance') || input.includes('candidate')) {
            const candidates = staffData.filter(staff => staff.isPromotionCandidate);

            if (candidates.length === 0) {
                return "There are currently no staff members identified as promotion candidates based on performance metrics.";
            }

            let response = `Based on performance data, I've identified <strong>${candidates.length} promotion candidates</strong>:<br><br>`;
            response += `<ul class="promotion-list">`;

            candidates.sort((a, b) => b.performance - a.performance).forEach(staff => {
                response += `<li><strong>${staff.name}</strong> (${staff.department}, ${staff.position}) - ${staff.performance}% performance, ${staff.experience} years experience</li>`;
            });

            response += `</ul><br>Would you like me to generate promotion documentation for any of these candidates?`;
            return response;
        }

        // Salary adjustment queries
        if (input.includes('salary') || input.includes('adjust') || input.includes('compensation')) {
            const eligibleStaff = staffData.filter(staff => staff.performance > 85);

            if (eligibleStaff.length === 0) {
                return "Currently, no staff members meet the criteria for salary adjustments (consistent performance above 85%).";
            }

            let response = `The salary adjustment algorithm suggests considering increases for <strong>${eligibleStaff.length} staff members</strong> with consistent high performance:<br><br>`;
            response += `<ul class="promotion-list">`;

            eligibleStaff.sort((a, b) => b.performance - a.performance).forEach(staff => {
                const increaseRange = staff.performance > 90 ? '10-15%' : '8-12%';
                response += `<li><strong>${staff.name}</strong>: Current performance ${staff.performance}%, recommended ${increaseRange} increase</li>`;
            });

            response += `</ul><br>Would you like me to prepare the adjustment proposals?`;
            return response;
        }

        // Performance queries
        if (input.includes('performance') || input.includes('metric') || input.includes('evaluation')) {
            const deptPerformance = {
                'Academic': 91,
                'Administrative': 78,
                'Support Staff': 82,
                'Management': 88,
                'Research': 85
            };

            let response = `<strong>Performance Metrics Overview:</strong><br><br>`;
            response += `• University-wide average: <?php echo round($avg_performance, 1); ?>%<br>`;
            response += `• Highest performing department: Academic (91%)<br>`;
            response += `• Lowest performing department: Administrative (78%)<br><br>`;

            if (input.includes('trend')) {
                response += `Performance trends show a 3% improvement overall compared to last quarter.<br>`;
                response += `The most improved department is Research (+5% from last quarter).`;
            } else {
                response += `Would you like me to analyze specific performance trends or comparisons?`;
            }

            return response;
        }

        // Staff-specific queries
        const mentionedStaff = staffData.find(staff =>
            input.includes(staff.first_name.toLowerCase()) ||
            input.includes(staff.last_name.toLowerCase()) ||
            input.includes(staff.name.toLowerCase())
        );

        if (mentionedStaff) {
            let response = `<strong>Staff Profile: ${mentionedStaff.name}</strong><br><br>`;
            response += `• Department: ${mentionedStaff.department}<br>`;
            response += `• Position: ${mentionedStaff.position}<br>`;
            response += `• Current Performance: ${mentionedStaff.performance}%<br>`;
            response += `• Experience: ${mentionedStaff.experience} years<br>`;
            response += `• Publications: ${mentionedStaff.publications}<br>`;
            response += `• Grants: ${mentionedStaff.grants}<br>`;
            response += `• Last Evaluation: ${mentionedStaff.lastEvaluation}<br><br>`;

            if (mentionedStaff.isPromotionCandidate) {
                response += `This staff member is identified as a <strong>promotion candidate</strong> based on consistent high performance.<br>`;
            } else if (mentionedStaff.performance < 70) {
                response += `This staff member <strong>requires performance improvement</strong> (below 70% threshold).<br>`;
            } else {
                response += `Performance is at satisfactory levels.<br>`;
            }

            response += `Would you like more details or specific recommendations for ${mentionedStaff.first_name}?`;
            return response;
        }

        // Default responses
        const randomResponses = [
            "I can analyze staff performance data to identify trends and make recommendations. What specific information would you like?",
            "Would you like me to generate a report on promotion candidates or salary adjustments?",
            "Sorry, I have not understood but identified several patterns in recent performance evaluations that might interest you.",
            "The decision support system can predict promotion success with 92% accuracy based on historical data.",
            "I can compare individual performance against department averages if that would be helpful."
        ];
        return randomResponses[Math.floor(Math.random() * randomResponses.length)];
    }

    // Handle form submission
    aiForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const userMessage = aiInput.value.trim();
        if (userMessage) {
            addMessage(userMessage, true);
            aiInput.value = '';

            const typingIndicator = showTypingIndicator();

            setTimeout(() => {
                removeTypingIndicator(typingIndicator);
                const aiResponse = getAIResponse(userMessage);
                addMessage(aiResponse);
                saveMessages();
            }, 1500);
        }
    });

    // Staff action buttons
    document.querySelectorAll('.action-btn.view').forEach(btn => {
        btn.addEventListener('click', function() {
            const staffId = this.getAttribute('data-user-id');
            const staff = staffData.find(s => s.staff_id === staffId);

            if (staff) {
                addMessage(`User requested details for ${staff.name}`, true);
                const typingIndicator = showTypingIndicator();

                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);
                    const response = `
                    <strong>${staff.name}</strong> (${staff.department})<br><br>
                    • Position: ${staff.position}<br>
                    • Performance: ${staff.performance}%<br>
                    • Experience: ${staff.experience} years<br>
                    • Last Evaluation: ${staff.lastEvaluation}<br>
                    • Status: ${staff.isPromotionCandidate ? 'Promotion Candidate' : 'Regular'}<br><br>
                    ${staff.isPromotionCandidate ? 
                        'This staff member qualifies for promotion consideration.' : 
                        staff.performance < 70 ? 
                        'Performance improvement plan recommended.' : 
                        'Performance at satisfactory levels.'
                    }
                `;
                    addMessage(response);
                    saveMessages();
                }, 1500);
            }
        });
    });

    document.querySelectorAll('.action-btn.promote').forEach(btn => {
        btn.addEventListener('click', function() {
            const staffId = this.getAttribute('data-user-id');
            const staff = staffData.find(s => s.staff_id === staffId);

            if (staff) {
                addMessage(`User initiated promotion process for ${staff.name}`, true);
                const typingIndicator = showTypingIndicator();

                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);
                    const response = `
                    <strong>Promotion Recommendation for ${staff.name}</strong><br><br>
                    • Current Position: ${staff.position}<br>
                    • Recommended Position: Senior ${staff.position}<br>
                    • Performance Justification: ${staff.performance}% average<br>
                    • Experience: ${staff.experience} years<br>
                    • Department Rank: Top ${staff.performance > 90 ? '5%' : '15%'}<br><br>
                    Would you like me to generate the promotion documentation?
                `;
                    addMessage(response);
                    saveMessages();
                }, 2000);
            }
        });
    });

    document.querySelectorAll('.action-btn.adjust').forEach(btn => {
        btn.addEventListener('click', function() {
            const staffId = this.getAttribute('data-staff-id');
            const staff = staffData.find(s => s.staff_id === staffId);

            if (staff) {
                addMessage(`User requested salary adjustment analysis for ${staff.name}`, true);
                const typingIndicator = showTypingIndicator();

                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);
                    const increaseRange = staff.performance > 90 ? '10-15%' :
                        staff.performance > 85 ? '8-12%' :
                        staff.performance > 80 ? '5-8%' : '0-3%';

                    const response = `
                    <strong>Salary Adjustment for ${staff.name}</strong><br><br>
                    • Current Performance: ${staff.performance}%<br>
                    • Department Average: ${staff.department === 'Academic' ? '91%' : 
                                        staff.department === 'Administrative' ? '78%' : 
                                        staff.department === 'Support Staff' ? '82%' : 
                                        staff.department === 'Management' ? '88%' : '85%'}<br>
                    • Market Benchmark: ${staff.position.includes('Senior') ? '15% above' : '5% above'} industry standard<br>
                    • Recommended Adjustment: ${increaseRange} increase<br><br>
                    Shall I prepare the adjustment proposal?
                `;
                    addMessage(response);
                    saveMessages();
                }, 2000);
            }
        });
    });

    // Load messages when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadMessages();

        // Initial assistant suggestions if no messages exist
        setTimeout(() => {
            if (conversation.querySelectorAll('.ai-message').length <= 1) {
                const typingIndicator = showTypingIndicator();

                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);
                    const initialMessage = "You can ask me things like:<br><br>" +
                        "• \"Show me promotion candidates\"<br>" +
                        "• \"Who qualifies for salary increases?\"<br>" +
                        "• \"Analyze performance trends\"<br>" +
                        "• \"Tell me about [staff name]\"";
                    addMessage(initialMessage);
                    saveMessages();
                }, 1500);
            }
        }, 3000);
    });
</script>