#ifndef PENDING_H
	#define PENDING_H
	
	int pending_add(char *number, char *message);
	int message_add(char *number, char *message);
	int pending_commit(int id);
	int failed_add(char *pdu);
	int unread_check();
	void pending_check();
#endif
